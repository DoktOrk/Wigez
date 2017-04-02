<?php

namespace Project\Domain\Entities;

use DateTime;
use Opulence\Orm\IEntity;

class File implements IEntity
{
    const DATE_FORMAT = 'Y-m-d';

    /** @var int */
    protected $id;

    /** @var string */
    protected $file;

    /** @var string */
    protected $description;

    /** @var DateTime */
    protected $uploadedAt;

    /** @var Category */
    protected $category;

    /**
     * @param int           $id
     * @param string        $file
     * @param string        $description
     * @param Category|null $category
     * @param DateTime      $uploadedAt
     */
    public function __construct(
        int $id,
        string $file,
        string $description,
        Category $category = null,
        DateTime $uploadedAt = null
    ) {
        $this->id          = $id;
        $this->file        = $file;
        $this->description = $description;
        $this->category    = $category;
        $this->uploadedAt  = $uploadedAt ?: new DateTime();
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
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return DateTime
     */
    public function getUploadedAt(): DateTime
    {
        return $this->uploadedAt;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        if (null === $this->category) {
            throw new \RuntimeException('Category is missing from file');
        }

        return $this->category;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->file;
    }
}