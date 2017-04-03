<?php

namespace Project\Domain\Entities;

class Customer implements IStringerEntity
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $email;

    /** @var Category[] */
    protected $categories = [];

    /** @var Category[] */
    protected $origCategories = [];

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

        /** @var Category $category */
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
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     *
     * @return array|Category[]
     */
    public function setCategories(array $categories)
    {
        $this->categories = $categories;

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

        return $deletedCategories;
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
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString():string
    {
        return $this->getName();
    }
}