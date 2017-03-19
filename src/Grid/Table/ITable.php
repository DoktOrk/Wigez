<?php

namespace Grid\Table;

use Grid\Collection\Cells;
use Grid\Collection\Rows;
use Grid\Component\IComponent;

interface ITable extends IComponent
{
    public function getHeader(): Cells;

    public function getRows(): Rows;
}
