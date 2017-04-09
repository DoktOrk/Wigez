<?php

namespace Project\Infrastructure\Orm\DataMappers;

use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\Mysql\SelectQuery;
use Project\Domain\Entities\Category as Entity;

class CategorySqlDataMapper extends SqlDataMapper implements ICategoryDataMapper
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
            ->insert('categories', ['name' => [$entity->getName(), \PDO::PARAM_STR]]);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $entity->setId($this->writeConnection->lastInsertId());
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
            ->update('categories', 'categories', ['deleted' => [1, \PDO::PARAM_INT]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_INT);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
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
     * @return array|null
     */
    public function getById($id)
    {
        $query = $this->getBaseQuery()->andWhere('categories.id = :category_id');

        $parameters = [
            'category_id' => [$id, \PDO::PARAM_INT],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $name
     *
     * @return array|null
     */
    public function getByName(string $name)
    {
        $query = $this->getBaseQuery()->andWhere('categories.name = :name');

        $parameters = ['name' => [$name, \PDO::PARAM_STR]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param int $customerId
     *
     * @return array|null
     */
    public function getByCustomerId(int $customerId)
    {
        $query = $this->getBaseQuery(true)->andWhere('categories.customer_id = :customer_id');

        $parameters = ['customer_id' => [$customerId, \PDO::PARAM_INT]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ARRAY);
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
            ->update('categories', 'categories', ['name' => [$entity->getName(), \PDO::PARAM_STR]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_INT);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @param array $hash
     *
     * @return Entity
     */
    protected function loadEntity(array $hash)
    {
        return new Entity(
            (int)$hash['id'],
            $hash['name']
        );
    }

    /**
     * @param bool $joinCustomer
     *
     * @return SelectQuery
     */
    private function getBaseQuery(bool $joinCustomer = false)
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select('categories.id', 'categories.name')
            ->from('categories')
            ->where('categories.deleted = 0');

        if ($joinCustomer) {
            $query->innerJoin('categories', 'categories', 'categories.id = categories_customers.category_id');
        }

        return $query;
    }
}
