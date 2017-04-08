<?php

namespace Project\Domain\Orm;

use Opulence\Orm\Repositories\Repository;
use Project\Domain\Entities\Customer;

class CategoryRepo extends Repository
{
    /**
     * @param Customer $category
     *
     * @return object|\object[]
     */
    public function getByCustomer(Customer $customer)
    {
        return $this->getFromDataMapper('getByCustomerId', [$customer->getId()]);
    }
}
