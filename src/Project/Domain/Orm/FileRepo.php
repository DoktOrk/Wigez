<?php

namespace Project\Domain\Orm;

use Opulence\Orm\Repositories\Repository;
use Project\Domain\Entities\Category;

class FileRepo extends Repository
{
    /**
     * @param Category $category
     *
     * @return object|\object[]
     */
    public function getByCategory(Category $category)
    {
        return $this->getFromDataMapper('getByCategoryId', [$category->getId()]);
    }
}
