<?php

namespace Project\Domain\Orm\DataMappers;

use Opulence\Orm\DataMappers\IDataMapper;

interface ICategoryDataMapper extends IDataMapper
{
    public function getByName(string $name);
}
