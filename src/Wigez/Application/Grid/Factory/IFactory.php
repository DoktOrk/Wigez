<?php

namespace Wigez\Application\Grid\Factory;

use Foo\Grid\Grid;

interface IFactory
{
    /**
     * @param array $pages
     *
     * @return Grid
     */
    public function createGrid(array $pages): Grid;
}


