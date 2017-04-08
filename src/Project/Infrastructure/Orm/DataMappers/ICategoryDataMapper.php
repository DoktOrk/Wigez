<?php

namespace Project\Infrastructure\Orm\DataMappers;

use Opulence\Orm\DataMappers\IDataMapper;

interface ICategoryDataMapper extends IDataMapper
{
    public function getByName(string $name);
}
