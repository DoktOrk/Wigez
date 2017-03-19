<?php

namespace Grid\Action;

use Grid\Component\IComponent;
use Grid\Component\Component;

class Button extends Component
{
    /**
     * @param string|IComponent $content
     * @param string $tag
     * @param array $attributes
     */
    public function __construct($content, string $tag = 'button', array $attributes = [])
    {
        parent::__construct($content, $tag, $attributes);
    }
}

