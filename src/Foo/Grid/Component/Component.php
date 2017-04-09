<?php

namespace Foo\Grid\Component;

use Foo\Grid\Helper\StringHelper;
use Foo\I18n\ITranslator;

class Component implements IComponent
{
    const ATTRIBUTE_CLASS = 'class';

    /** @var string|IComponent */
    protected $content;

    /** @var string|null */
    protected $tag;

    /** @var array */
    protected $attributes = [];

    /** @var string */
    protected $indentation = '';

    /** @var ITranslator */
    protected $translator;

    /**
     * Component constructor.
     *
     * @param string           $content
     * @param string|null      $tag
     * @param array            $attributes
     * @param ITranslator|null $translator
     */
    public function __construct(
        $content = '',
        string $tag = null,
        array $attributes = [],
        ITranslator $translator = null
    ) {
        $this->content    = $content;
        $this->tag        = $tag;
        $this->attributes = $attributes;
        $this->translator = $translator;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return (string)$this->content;
    }

    /**
     * @return string
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $attribute
     * @param string $valueToAppend
     */
    public function appendToAttribute(string $attribute, string $valueToAppend)
    {
        $currentValue = isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : '';

        $classes = explode(' ', $currentValue);

        $classes[] = $valueToAppend;

        $classes = array_unique($classes);
        $classes = array_filter($classes);

        $this->attributes[$attribute] = implode(' ', $classes);
    }

    /**
     * @param int    $num
     * @param string $whitespace
     */
    public function setIndentation(int $num, string $whitespace = '    ')
    {
        $this->intendation = str_repeat($whitespace, $num);
    }

    /**
     * @param ITranslator $translator
     */
    public function setTranslator(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $content = $this->content;

        if ($this->translator) {
            $content = $this->translator->translate($this->content);

            if (substr($content, 0, 2) === '{{') {
                $content = $this->content;
            }
        }

        return StringHelper::wrapInTag($content, $this->tag, $this->attributes);
    }
}

