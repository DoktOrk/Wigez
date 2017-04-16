<?php

namespace Wigez\Domain\Entities;

use Opulence\Orm\IEntity;

interface IStringerEntity extends IEntity
{
    public function __toString(): string;
}