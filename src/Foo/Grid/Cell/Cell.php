<?php

namespace Foo\Grid\Cell;

use Foo\Grid\Component\Component;
use Foo\Grid\Component\IComponent;

class Cell extends Component implements ICell
{
    const HEAD = 'th';
    const BODY = 'td';

    /** @var string */
    protected $group = '';

    /**
     * @param string|IComponent $content
     * @param string            $group
     * @param array             $attributes
     * @param string            $tag
     */
    public function __construct(string $content, string $group, array $attributes = [], string $tag = self::BODY)
    {
        $this->group = $group;

        parent::__construct($content, $tag, $attributes);

        $this->appendToAttribute(Component::ATTRIBUTE_CLASS, $tag . '-' . $group);
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }
}
