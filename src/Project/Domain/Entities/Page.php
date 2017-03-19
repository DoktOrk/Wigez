<?php

namespace Project\Domain\Entities;

use Opulence\Orm\IEntity;

class Page implements IEntity
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $title;

    /** @var string */
    protected $body;

    /**
     * Page constructor.
     *
     * @param int $id
     * @param string $title
     * @param string $body
     */
    public function __construct(int $id, string $title, string $body)
    {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}