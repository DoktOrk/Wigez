<?php

namespace Grid\Cell;

use Grid\Component\IComponent;

interface ICell extends IComponent
{
    public function getGroup(): string;

    public function getContent(): string;
}
