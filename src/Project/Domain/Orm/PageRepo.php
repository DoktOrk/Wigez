<?php

namespace Project\Domain\Orm;

use Opulence\Orm\Repositories\Repository;

class PageRepo extends Repository
{
    /**
     * @param string $title
     *
     * @return object|\object[]
     */
    public function getByTitle(string $title)
    {
        return $this->getFromDataMapper('getByTitle', [$title]);
    }
}
