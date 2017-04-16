<?php

namespace Wigez\Infrastructure\Orm;

use Opulence\Orm\Repositories\Repository;
use Wigez\Domain\Entities\Customer;
use Wigez\Domain\Entities\File;

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
