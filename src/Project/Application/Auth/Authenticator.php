<?php

namespace Project\Application\Auth;

use Opulence\Databases\ConnectionPools\ConnectionPool;
use Opulence\QueryBuilders\MySql\QueryBuilder;

class Authenticator
{
    /** @var ConnectionPool */
    protected $connectionPool;

    /**
     * Authenticator constructor.
     *
     * @param ConnectionPool $connectionPool
     */
    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function canLogin(string $username, string $password): bool
    {
        $readConnection = $this->connectionPool->getReadConnection();

        $query = (new QueryBuilder())->select('id')
            ->from('users')
            ->where('username = :username')
//            ->andWhere('password = :password')
//            ->addNamedPlaceholderValue('password', $password)
            ->addNamedPlaceholderValue('username', $username);

        $statement = $readConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        return !empty($row);
    }
}

