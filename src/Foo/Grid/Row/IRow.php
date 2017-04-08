<?php

namespace Foo\Grid\Row;

use Foo\Grid\Collection\Actions;
use Foo\Grid\Collection\Cells;
use Foo\Grid\Component\IComponent;
use Opulence\Orm\IEntity;

interface IRow extends IComponent
{
    public function getCells(): Cells;

    public function getActions(): Actions;

    public function setEntity(IEntity $entity);

    public function getEntity(): IEntity;
}
