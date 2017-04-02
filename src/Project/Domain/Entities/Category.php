<?php

namespace Project\Domain\Entities;

use Opulence\Orm\IEntity;

class Category implements IEntity
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var int */
    protected $pos;

    /**
     * @param int    $id
     * @param string $name
     * @param int    $pos
     */
    public function __construct(int $id, string $name, int $pos)
    {
        $this->id   = $id;
        $this->name = $name;
        $this->pos  = $pos;
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
     * @return int
     */
    public function getPos(): int
    {
        return $this->pos;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }
}