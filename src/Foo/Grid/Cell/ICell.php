<?php

namespace Foo\Grid\Cell;

use Foo\Grid\Component\IComponent;

interface ICell extends IComponent
{
    public function getGroup(): string;

    public function getContent(): string;
}
