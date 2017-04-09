<?php

namespace Foo\Grid\Action;

use Foo\Grid\Component\Component;
use Foo\Grid\Component\IComponent;
use Opulence\Orm\IEntity;

class Button extends Component implements IAction
{
    const TAG_A      = 'a';
    const TAG_BUTTON = 'button';

    /** @var IEntity */
    protected $entity;

    /** @var array */
    protected $attributeCallbacks = [];

    /**
     * @param string|IComponent $content
     * @param string            $tag
     * @param array             $attributes
     * @param array             $attributeCallbacks
     */
    public function __construct(
        $content,
        string $tag = self::TAG_A,
        array $attributes = [],
        array $attributeCallbacks = []
    ) {
        $this->attributeCallbacks = $attributeCallbacks;

        parent::__construct($content, $tag, $attributes);
    }

    /**
     * @return \Closure
     */
    public static function getDefaultCallback(): \Closure
    {
        return function ($attribute, IEntity $entity) {
            return sprintf($attribute, $entity->getId());
        };
    }

    /**
     * @param IEntity $entity
     */
    public function setEntity(IEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        foreach ($this->attributeCallbacks as $attribute => $callback) {
            $this->attributes[$attribute] = $callback($this->attributes[$attribute], $this->entity);
        }

        return parent::__toString();
    }

    /**
     * @return IAction
     */
    public function duplicate(): IAction
    {
        return new Button($this->content, $this->tag, $this->attributes, $this->attributeCallbacks);
    }
}

