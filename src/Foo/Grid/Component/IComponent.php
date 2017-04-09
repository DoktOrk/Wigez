<?php

namespace Foo\Grid\Component;

use Foo\I18n\ITranslator;

interface IComponent
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @param int    $num
     * @param string $whitespace
     */
    public function setIndentation(int $num, string $whitespace = ' ');

    /**
     * @param ITranslator $translator
     */
    public function setTranslator(ITranslator $translator);
}
