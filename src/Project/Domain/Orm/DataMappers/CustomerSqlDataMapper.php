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

        $values = [
            'name'     => [$entity->getName(), \PDO::PARAM_STR],
            'email'    => [$entity->getEmail(), \PDO::PARAM_STR],
            'password' => [$entity->getPassword(), \PDO::PARAM_STR],
        ];
        $statement = $this->writeConnection->prepare(
            'INSERT INTO customers (`name`, email, password) VALUES (:name, :email, :password)'
        );
        $statement->bindValues($values);
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

        $values = [
            'id' => [$entity->getId(), \PDO::PARAM_INT],
        ];
        $statement = $this->writeConnection->prepare(
            'UPDATE customers SET deleted=1 WHERE id = :id'
        );
        $statement->bindValues($values);
        $statement->execute();

        $this->updateCategories($entity);
    }

    /**
     * @param Entity $entity
     */
    private function updateCategories(Entity $entity)
    {
        foreach ($entity->getDeletedCategories() as $category) {
            $values = [
                'customer_id' => [$entity->getId(), \PDO::PARAM_INT],
                'category_id' => [$category->getId(), \PDO::PARAM_INT],
            ];
            $statement = $this->writeConnection->prepare(
                'DELETE FROM categories_customers WHERE customer_id = :customer_id AND category_id = :category_id'
            );
            $statement->bindValues($values);
            $statement->execute();
        }

        foreach ($entity->getNewCategories() as $category) {
            $values = [
                'customer_id' => [$entity->getId(), \PDO::PARAM_INT],
                'category_id' => [$category->getId(), \PDO::PARAM_INT],
            ];
            $statement = $this->writeConnection->prepare(
                'INSERT INTO categories_customers SET customer_id = :customer_id, category_id = :category_id'
            );
            $statement->bindValues($values);
            $statement->execute();
        }
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $sql = $this->getQuery();

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
        $sqlParts[] = 'LEFT JOIN categories ON categories.id=categories_customers.category_id AND categories.deleted=0';
        $sqlParts[] = 'WHERE';
        $sqlParts[] = implode(' AND ', $where);
        $sqlParts[] = '  AND customers.deleted = 0';
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
        $sql        = $this->getQuery(['customers.id = :customer_id']);
        $parameters = [
            'customer_id' => [$id, \PDO::PARAM_INT],
        ];

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $name
     *
     * @return array|null
     */
    public function getByName(string $name)
    {
        $parameters = ['customers.name' => [$name, \PDO::PARAM_STR]];
        $sql        = $this->getQuery(['`name` = :name']);

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param string $identifier
     *
     * @return array|null
     */
    public function find(string $identifier)
    {
        $parameters = ['identifier' => [$identifier, \PDO::PARAM_STR]];
        $sql        = $this->getQuery(['(customers.`name` = :identifier OR customers.`email` = :identifier)']);

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param string $email
     *
     * @return array|null
     */
    public function getByEmail(string $email)
    {
        $parameters = ['email' => [$email, \PDO::PARAM_STR]];
        $sql        = $this->getQuery(['customers.`email` = :email']);

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

        $values = [
            'name'     => [$entity->getName(), \PDO::PARAM_STR],
            'email'    => [$entity->getEmail(), \PDO::PARAM_STR],
            'password' => [$entity->getPassword(), \PDO::PARAM_STR],
            'id'       => [$entity->getId(), \PDO::PARAM_INT],
        ];
        $statement = $this->writeConnection->prepare(
            'UPDATE customers SET `name` = :name, email = :email, password = :password WHERE id = :id'
        );
        $statement->bindValues($values);
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
}
