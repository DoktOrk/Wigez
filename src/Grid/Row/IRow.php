<?php

namespace Grid\Row;

use Grid\Collection\Actions;
use Grid\Collection\Cells;
use Grid\Component\IComponent;
use Opulence\Orm\IEntity;

interface IRow extends IComponent
{
    public function getCells(): Cells;

    public function getActions(): Actions;

    public function setEntity(IEntity $entity);

    public function getEntity(): IEntity;
}
