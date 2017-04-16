<?php

namespace Wigez\Infrastructure\Orm\DataMappers;

use Opulence\Orm\DataMappers\IDataMapper;

interface IUserDataMapper extends IDataMapper
{
    public function getByUsername(string $identifier);

    public function getByEmail(string $email);

    public function find(string $identifier);
}
