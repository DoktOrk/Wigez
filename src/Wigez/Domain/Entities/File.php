<?php

namespace Wigez\Domain\Entities;

use DateTime;

class File implements IStringerEntity
{
    const DATE_FORMAT = 'Y-m-d';

    /** @var int */
    protected $id;

    /** @var string */
    protected $file;

    /** @var string */
    protected $oldFile;

    /** @var string */
    protected $filename;

    /** @var string */
    protected $description;

    /** @var DateTime */
    protected $uploadedAt;

    /** @var Category */
    protected $category;

    /**
     * @param int           $id
     * @param string        $file
     * @param string        $filename
     * @param string        $description
     * @param Category|null $category
     * @param DateTime      $uploadedAt
     */
    public function __construct(
        int $id,
        string $file,
        string $filename,
        string $description,
        Category $category = null,
        DateTime $uploadedAt = null
    ) {
        $this->id          = $id;
        $this->file        = $file;
        $this->oldFile     = $file;
        $this->filename    = $filename;
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
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @param string $file
     *
     * @return File
     */
    public function setFile(string $file): File
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getOldFile(): string
    {
        return $this->oldFile;
    }

    /**
     * @return bool
     */
    public function isFileUploaded(): bool
    {
        return $this->oldFile !== $this->file;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $file
     *
     * @return File
     */
    public function setFilename(string $filename): File
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return File
     */
    public function setDescription(string $description): File
    {
        $this->description = $description;

        return $this;
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
     * @param Category $category
     *
     * @return File
     */
    public function setCategory(Category $category): File
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUploadedAt(): DateTime
    {
        return $this->uploadedAt;
    }

    /**
     * @param string $uploadedAt
     *
     * @return File
     */
    public function setUploadedAt(\DateTime $uploadedAt): File
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if (!$this->file) {
            return '#' . $this->getId();
        }

        return $this->file;
    }
}