<?php

namespace Project\Domain\Orm\DataMappers;

use Opulence\Orm\DataMappers\IDataMapper;

interface ICustomerDataMapper extends IDataMapper
{
    public function getByName(string $name);

    public function getByEmail(string $email);
}
