<?php

namespace Project\Application\Auth;

use Opulence\Cryptography\Hashing\BcryptHasher;
use Opulence\Databases\ConnectionPools\ConnectionPool;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Project\Application\Constant\Env;

class Authenticator
{
    /** @var ConnectionPool */
    protected $connectionPool;

    /** @var BcryptHasher */
    protected $bcryptHasher;

    /**
     * Authenticator constructor.
     *
     * @param ConnectionPool $connectionPool
     * @param BcryptHasher $bcryptHasher
     */
    public function __construct(ConnectionPool $connectionPool, BcryptHasher $bcryptHasher)
    {
        $this->connectionPool = $connectionPool;
        $this->bcryptHasher = $bcryptHasher;
    }

    /**
     * @param string $username
     * @param string $oassword
     *
     * @return bool
     */
    public function canLogin(string $username, string $oassword): bool
    {
        $salt = getenv(Env::ENCRYPTION_KEY);

        $hashedValue = $this->getUserPassword($username);
        if (empty($hashedValue)) {
            return false;
        }

        $hashedValue = $this->bcryptHasher->hash($oassword, [], $salt);

        $verified = $this->bcryptHasher->verify($hashedValue, $oassword, $salt);

        return $verified;
    }

    /**
     * @param string $username
     *
     * @return string
     */
    public function getUserPassword(string $username) :string
    {
        $readConnection = $this->connectionPool->getReadConnection();

        $query = (new QueryBuilder())->select('password')
            ->from('users')
            ->where('username = :username')
            ->addNamedPlaceholderValue('username', $username);

        $statement = $readConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!isset($row['password'])) {
            return '';
        }

        return $row['password'];
    }
}

