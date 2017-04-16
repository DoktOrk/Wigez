<?php

namespace Wigez\Infrastructure\Orm\DataMappers;

use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;
use Wigez\Domain\Entities\User as Entity;

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

        $query = (new QueryBuilder())
            ->insert(
                'users',
                [
                    'username' => [$entity->getUsername(), \PDO::PARAM_STR],
                    'email'    => [$entity->getEmail(), \PDO::PARAM_STR],
                    'password' => [$entity->getPassword(), \PDO::PARAM_STR],
                ]
            );

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
            ->update('users', 'users', ['deleted' => [1, \PDO::PARAM_INT]])
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
     * @return Entity|null
     */
    public function getById($id)
    {
        $query = $this->getBaseQuery()->andWhere('users.id = :user_id');

        $parameters = ['user_id' => [$id, \PDO::PARAM_INT]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $identifier
     *
     * @return array|null
     */
    public function find(string $identifier)
    {
        $query = $this->getBaseQuery()->andWhere('(username = :identifier OR email = :identifier)');

        $parameters = ['identifier' => [$identifier, \PDO::PARAM_STR]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param string $username
     *
     * @return Entity|null
     */
    public function getByUsername(string $username)
    {
        $query = $this->getBaseQuery()->andWhere('`username` = :username');

        $parameters = ['username' => [$username, \PDO::PARAM_STR]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $email
     *
     * @return Entity|null
     */
    public function getByEmail(string $email)
    {
        $query = $this->getBaseQuery()->andWhere('email = :email');

        $parameters = ['email' => [$email, \PDO::PARAM_STR]];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
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
                'users',
                'users',
                [
                    'username' => [$entity->getUsername(), \PDO::PARAM_STR],
                    'email'    => [$entity->getEmail(), \PDO::PARAM_STR],
                    'password' => [$entity->getPassword(), \PDO::PARAM_STR],
                ]
            )
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
            $hash['username'],
            $hash['email'],
            $hash['password']
        );
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery()
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select('users.id', 'users.username', 'users.email', 'users.password')
            ->from('users')
            ->where('users.deleted = 0');

        return $query;
    }
}
