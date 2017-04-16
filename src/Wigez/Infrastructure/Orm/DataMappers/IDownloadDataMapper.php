<?php

namespace Wigez\Infrastructure\Orm\DataMappers;

use Opulence\Orm\DataMappers\IDataMapper;

interface IDownloadDataMapper extends IDataMapper
{
    public function getByFileId(int $fileId);

    public function getByCustomerId(int $fileId);
}
