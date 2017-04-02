<?php

namespace Project\Domain\Orm;

use Opulence\Orm\Repositories\Repository;

class CustomerRepo extends Repository
{
    /**
     * @param string $name
     *
     * @return object|\object[]
     */
    public function getByName(string $name)
    {
        return $this->getFromDataMapper('getByName', [$name]);
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
}
