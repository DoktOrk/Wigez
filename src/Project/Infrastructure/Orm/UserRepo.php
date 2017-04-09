<?php

namespace Project\Infrastructure\Orm;

use Opulence\Orm\Repositories\Repository;

class UserRepo extends Repository
{
    /**
     * @param string $username
     *
     * @return object|\object[]
     */
    public function getByUsername(string $username)
    {
        return $this->getFromDataMapper('getByUsername', [$username]);
    }

    /**
     * @param string $email
     *
     * @return object|\object[]
     */
    public function getByEmail(string $email)
    {
        return $this->getFromDataMapper('getByEmail', [$email]);
    }

    /**
     * @param string $identifier
     *
     * @return object|\object[]
     */
    public function find(string $identifier)
    {
        return $this->getFromDataMapper('find', [$identifier]);
    }
}
