<?php

namespace Grid\Row;

use Grid\Collection\Actions;
use Grid\Collection\Cells;
use Grid\Component\IComponent;

interface IRow extends IComponent
{
    public function getCells(): Cells;

    public function getActions(): Actions;
}
