<?php

namespace Foo\Grid\Table;

use Foo\Grid\Collection\Cells;
use Foo\Grid\Collection\Rows;
use Foo\Grid\Component\IComponent;

interface ITable extends IComponent
{
    public function getHeader(): Cells;

    public function getRows(): Rows;
}
