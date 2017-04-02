<?php

namespace Project\Domain\Entities;

use Opulence\Orm\IEntity;

class Customer implements IEntity
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $email;

    /** @var Category[] */
    protected $categories;

    /** @var Category[] */
    protected $origCategories;

    /** @var string */
    protected $password;

    /**
     * @param int        $id
     * @param string     $name
     * @param string     $email
     * @param Category[] $categories
     * @param string     $password
     */
    public function __construct(
        int $id,
        string $name,
        string $email,
        array $categories,
        string $password
    ) {
        $this->id             = $id;
        $this->name           = $name;
        $this->email          = $email;
        $this->password       = $password;

        foreach ($categories as $category) {
            $this->categories[$category->getId()] = $category;
        }

        $this->origCategories = $this->categories;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return Category[]
     */
    public function getDeletedCategories(): array
    {
        $origCategories = $this->origCategories;

        $deletedCategories = [];
        foreach ($origCategories as $id => $category) {
            if (isset($this->categories[$id])) {
                continue;
            }

            $deletedCategories[$id] = $category;
        }

        return array($deletedCategories);
    }

    /**
     * @return Category[]
     */
    public function getNewCategories(): array
    {
        $categories = $this->categories;

        $newCategories = [];
        foreach ($categories as $id => $category) {
            if (isset($this->origCategories[$id])) {
                continue;
            }

            $newCategories[$id] = $category;
        }

        return array_values($newCategories);
    }


    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function __toString():string
    {
        return $this->getName();
    }
}