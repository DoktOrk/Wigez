<?php

namespace Project\Infrastructure\Orm\DataMappers;

use Opulence\Orm\DataMappers\SqlDataMapper;
use Project\Domain\Entities\User as Entity;

class UserSqlDataMapper extends SqlDataMapper implements IUserDataMapper
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
            'username' => [$entity->getUsername(), \PDO::PARAM_STR],
            'email'    => [$entity->getEmail(), \PDO::PARAM_STR],
            'password' => [$entity->getPassword(), \PDO::PARAM_STR],
        ];
        $statement = $this->writeConnection->prepare(
            'INSERT INTO users (`username`, email, password) VALUES (:username, :email, :password)'
        );
        $statement->bindValues($values);
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

        $values = [
            'id' => [$entity->getId(), \PDO::PARAM_INT],
        ];
        $statement = $this->writeConnection->prepare(
            'UPDATE users SET deleted=1 WHERE id = :id'
        );
        $statement->bindValues($values);
        $statement->execute();
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

        $sqlParts[] = 'SELECT users.id, users.`username`, users.email, users.`password`';
        $sqlParts[] = 'FROM users';
        $sqlParts[] = 'WHERE';
        $sqlParts[] = implode(' AND ', $where);
        $sqlParts[] = '  AND users.deleted = 0';

        return implode(' ', $sqlParts);
    }

    /**
     * @param int|string $id
     *
     * @return array|null
     */
    public function getById($id)
    {
        $sql        = $this->getQuery(['users.id = :user_id']);
        $parameters = [
            'user_id' => [$id, \PDO::PARAM_INT],
        ];

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $identifier
     *
     * @return array|null
     */
    public function find(string $identifier)
    {
        $parameters = ['identifier' => [$identifier, \PDO::PARAM_STR]];
        $sql        = $this->getQuery(['(username = :identifier OR email = :identifier)']);

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param string $username
     *
     * @return array|null
     */
    public function getByUsername(string $username)
    {
        $parameters = ['username' => [$username, \PDO::PARAM_STR]];
        $sql        = $this->getQuery(['`username` = :username']);

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

        $values = [
            'username' => [$entity->getUsername(), \PDO::PARAM_STR],
            'email'    => [$entity->getEmail(), \PDO::PARAM_STR],
            'password' => [$entity->getPassword(), \PDO::PARAM_STR],
            'id'       => [$entity->getId(), \PDO::PARAM_INT],
        ];
        $statement = $this->writeConnection->prepare(
            'UPDATE users SET `username` = :username, email = :email, password = :password WHERE id = :id'
        );
        $statement->bindValues($values);
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
            $hash['username'],
            $hash['email'],
            $hash['password']
        );
    }
}
