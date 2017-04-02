<?php

namespace Project\Domain\Orm\DataMappers;

use Opulence\Orm\DataMappers\SqlDataMapper;
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

        $statement = $this->writeConnection->prepare(
            'INSERT INTO customers (`name`, email, password, password_sent) VALUES (:name, :email, :password, :password_sent)'
        );
        $statement->bindValues(
            [
                'name'          => $entity->getName(),
                'email'         => $entity->getEmail(),
                'password'      => $entity->getPassword(),
                'password_sent' => $entity->getPasswordSent(),
            ]
        );
        $statement->execute();
    }

    /**
     * @param Entity $entity
     */
    public function delete($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Customer entity.');
        }

        $statement = $this->writeConnection->prepare(
            'DELETE FROM customers WHERE id = :id'
        );
        $statement->bindValues(
            [
                'id' => [$entity->getId(), \PDO::PARAM_INT],
            ]
        );
        $statement->execute();

        $this->updateCategories($entity);
    }

    /**
     * @param Entity $entity
     */
    private function updateCategories(Entity $entity)
    {
        foreach ($entity->getDeletedCategories() as $category) {
            $statement = $this->writeConnection->prepare(
                'DELETE FROM categories_customers WHERE customer_id = :customer_id'
            );
            $statement->bindValues(
                [
                    'customer_id' => [$entity->getId(), \PDO::PARAM_INT],
                    'category_id' => [$category->getId(), \PDO::PARAM_INT],
                ]
            );
            $statement->execute();
        }

        foreach ($entity->getNewCategories() as $category) {
            $statement = $this->writeConnection->prepare(
                'INSERT INTO categories_customers VALUES customer_id = :customer_id, category_id = :category_id'
            );
            $statement->bindValues(
                [
                    'customer_id' => [$entity->getId(), \PDO::PARAM_INT],
                    'category_id' => [$category->getId(), \PDO::PARAM_INT],
                ]
            );
            $statement->execute();
        }
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $sql = $this->getQuery();

        // The last parameter says that we want a list of entities
        return $this->read($sql, [], self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param array $where
     *
     * @return string
     */
    private function getQuery(array $where = [1])
    {
        $sqlParts = [];

        $sqlParts[] = 'SELECT customers.id, customers.`name`, customers.email, customers.`password`,';
        $sqlParts[] = '  GROUP_CONCAT(CONCAT(categories.`id`, \'-\', categories.`name`)) AS categories';
        $sqlParts[] = 'FROM customers';
        $sqlParts[] = 'LEFT JOIN categories_customers ON categories_customers.customer_id=customers.id';
        $sqlParts[] = 'LEFT JOIN categories ON categories.id=categories_customers.category_id';
        $sqlParts[] = 'WHERE';
        $sqlParts[] = implode(', ', $where);
        $sqlParts[] = 'GROUP BY customers.id';

        return implode(' ', $sqlParts);
    }

    /**
     * @param int|string $id
     *
     * @return array|null
     */
    public function getById($id)
    {
        $sql        = $this->getQuery(['`id` = :id']);
        $parameters = [
            'id' => [$id, \PDO::PARAM_INT],
        ];

        // The second-to-last parameter says that we want a single entity
        // The last parameter says that we expect one and only one entity
        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $name
     *
     * @return array|null
     */
    public function getByName(string $name)
    {
        $parameters = ['name' => $name];
        $sql        = $this->getQuery(['`name` = :name']);

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param string $email
     *
     * @return array|null
     */
    public function getByEmail(string $email)
    {
        $parameters = ['email' => $email];
        $sql        = $this->getQuery(['`email` = :email']);

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param Entity $entity
     */
    public function update($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Customer entity.');
        }

        $statement = $this->writeConnection->prepare(
            'UPDATE customers SET `name` = :name, email = :email, password = :password WHERE id = :id'
        );
        $statement->bindValues(
            [
                'name'     => $entity->getName(),
                'email'    => $entity->getEmail(),
                'password' => $entity->getPassword(),
                'id'       => [$entity->getId(), \PDO::PARAM_INT],
            ]
        );
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
        foreach ($categoryExploded as $categoryData) {
            list($id, $name) = explode('-', $categoryData);

            $categories[] = new Category($id, $name);
        }

        return new Entity(
            (int)$hash['id'],
            $hash['name'],
            $hash['email'],
            $categories,
            $hash['password']
        );
    }
}
