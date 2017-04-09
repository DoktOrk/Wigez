<?php

namespace Project\Infrastructure\Orm\DataMappers;

use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;
use Project\Domain\Entities\Category;
use Project\Domain\Entities\Customer as Entity;

class CustomerSqlDataMapper extends SqlDataMapper implements ICustomerDataMapper
{
    /**
     * @param Entity $entity
     */
    public function add($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Customer entity.');
        }

        $query = (new QueryBuilder())
            ->insert(
                'customers',
                [
                    'name'     => [$entity->getName(), \PDO::PARAM_STR],
                    'email'    => [$entity->getEmail(), \PDO::PARAM_STR],
                    'password' => [$entity->getPassword(), \PDO::PARAM_STR],
                ]
            );

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $entity->setId($this->writeConnection->lastInsertId());

        $this->updateCategories($entity);
    }

    /**
     * @param Entity $entity
     */
    public function delete($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Customer entity.');
        }

        $query = (new QueryBuilder())
            ->update('customers', 'customers', ['deleted' => [1, \PDO::PARAM_INT]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue( $entity->getId(), \PDO::PARAM_INT);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $this->updateCategories($entity);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $query = $this->getBaseQuery();

        return $this->read($query->getSql(), [], self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param int|string $id
     *
     * @return Entity|null
     */
    public function getById($id)
    {
        $query = $this->getBaseQuery()->andWhere('customers.id = :customer_id');

        $parameters = ['customer_id' => [$id, \PDO::PARAM_INT]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $name
     *
     * @return Entity|null
     */
    public function getByName(string $name)
    {
        $query = $this->getBaseQuery()->andWhere('name = :name');

        $parameters = ['customers.name' => [$name, \PDO::PARAM_STR]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param string $identifier
     *
     * @return array|null
     */
    public function find(string $identifier)
    {
        $query = $this->getBaseQuery()->andWhere('(customers.`name` = :identifier OR customers.`email` = :identifier)');

        $parameters = ['identifier' => [$identifier, \PDO::PARAM_STR]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param string $email
     *
     * @return Entity|null
     */
    public function getByEmail(string $email)
    {
        $query = $this->getBaseQuery()->andWhere('customers.email = :email');

        $parameters = ['email' => [$email, \PDO::PARAM_STR]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param Entity $entity
     */
    public function update($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Customer entity.');
        }

        $query = (new QueryBuilder())
            ->update(
                'customers',
                'customers',
                [
                    'name'     => [$entity->getName(), \PDO::PARAM_STR],
                    'email'    => [$entity->getEmail(), \PDO::PARAM_STR],
                    'password' => [$entity->getPassword(), \PDO::PARAM_STR],
                ]
            )
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_INT);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $this->updateCategories($entity);
    }

    /**
     * @param array $hash
     *
     * @return Entity
     */
    protected function loadEntity(array $hash)
    {
        $categories = [];

        $categoryExploded = explode(',', $hash['categories']);
        $categoryExploded = array_filter($categoryExploded);
        foreach ($categoryExploded as $categoryData) {
            list($id, $name) = explode('-', $categoryData, 2);

            $categories[] = new Category((int)$id, $name);
        }

        return new Entity(
            (int)$hash['id'],
            $hash['name'],
            $hash['email'],
            $categories,
            $hash['password']
        );
    }

    /**
     * @param Entity $entity
     */
    private function updateCategories(Entity $entity)
    {
        foreach ($entity->getDeletedCategories() as $category) {
            $query = (new QueryBuilder)->delete('categories_customers')
                ->where('customer_id = :customer_id')
                ->andWhere('category_id = :category_id')
                ->addNamedPlaceholderValue('customer_id', $entity->getId(), \PDO::PARAM_INT)
                ->addNamedPlaceholderValue('category_id', $category->getId(), \PDO::PARAM_INT);

            $statement = $this->writeConnection->prepare($query->getSql());
            $statement->bindValues($query->getParameters());
            $statement->execute();
        }

        foreach ($entity->getNewCategories() as $category) {
            $query = (new QueryBuilder())
                ->insert(
                    'categories_customers',
                    [
                        'customer_id' => [$entity->getId(), \PDO::PARAM_INT],
                        'category_id' => [$category->getId(), \PDO::PARAM_INT],
                    ]
                );

            $statement = $this->writeConnection->prepare($query->getSql());
            $statement->bindValues($query->getParameters());
            $statement->execute();
        }
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery()
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'customers.id',
                'customers.name',
                'customers.email',
                'customers.password',
                'GROUP_CONCAT(CONCAT(categories.`id`, \'-\', categories.`name`)) AS categories'
            )
            ->from('customers')
            ->leftJoin(
                'categories_customers',
                'categories_customers',
                'categories_customers.customer_id=customers.id'
            )
            ->leftJoin(
                'categories',
                'categories',
                'categories.id=categories_customers.category_id AND categories.deleted=0'
            )
            ->where('customers.deleted = 0')
            ->groupBy('customers.id');

        return $query;
    }
}
