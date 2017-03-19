<?php

namespace Project\Domain\Orm\DataMappers;

use Opulence\Orm\DataMappers\IDataMapper;

interface IPageDataMapper extends IDataMapper
{
    public function getByTitle(string $title);
}
