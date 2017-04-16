<?php

namespace Wigez\Infrastructure\Orm\DataMappers;

use Opulence\Orm\DataMappers\IDataMapper;

interface IPageDataMapper extends IDataMapper
{
    public function getByTitle(string $title);
}
