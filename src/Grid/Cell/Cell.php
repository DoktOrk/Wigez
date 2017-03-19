<?php

namespace Grid\Cell;

use Grid\Component\IComponent;
use Grid\Component\Component;

class Cell extends Component implements ICell
{
    /** @var string */
    protected $group = '';

    /**
     * @param string|IComponent $content
     * @param string $group
     * @param array $attributes
     * @param string $tag
     */
    public function __construct(string $content, string $group, array $attributes = [], string $tag = 'td')
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
