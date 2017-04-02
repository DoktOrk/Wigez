<?php

namespace Project\Domain\Orm;

use Opulence\Orm\Repositories\Repository;
use Project\Domain\Entities\Customer;
use Project\Domain\Entities\File;

class DownloadRepo extends Repository
{
    /**
     * @param File $file
     *
     * @return object|\object[]
     */
    public function getByFile(File $file)
    {
        return $this->getFromDataMapper('getByFileId', [$file->getId()]);
    }
    /**
     * @param Customer $customer
     *
     * @return object|\object[]
     */
    public function getByCustomer(Customer $customer)
    {
        return $this->getFromDataMapper('getByCustomerId', [$customer->getId()]);
    }
}
