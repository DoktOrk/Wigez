<?php

namespace Project\Domain\Entities;

use DateTime;
use Opulence\Orm\IEntity;

class Download implements IEntity
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var int */
    protected $id;

    /** @var File */
    protected $file;

    /** @var Customer */
    protected $customer;

    /** @var DateTime */
    protected $downloadedAt;

    /**
     * @param int      $id
     * @param File     $file
     * @param Customer $customer
     * @param DateTime $downloadedAt
     */
    public function __construct(int $id, File $file, Customer $customer, DateTime $downloadedAt = null)
    {
        $this->id           = $id;
        $this->file         = $file;
        $this->customer     = $customer;
        $this->downloadedAt = $downloadedAt ?: new DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @return DateTime
     */
    public function getDownloadedAt(): DateTime
    {
        return $this->downloadedAt;
    }
}