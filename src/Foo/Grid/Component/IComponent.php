<?php

namespace Foo\Grid\Component;

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
}
