<?php

namespace Wigez\Infrastructure\Orm;

use Opulence\Orm\Repositories\Repository;
use Wigez\Domain\Entities\Category;

class FileRepo extends Repository
{
    /**
     * @param Category[] $categories
     *
     * @return object|\object[]
     */
    public function getByCategories(array $categories)
    {
        $categoryIds = array_map(
            function (Category $category) {
                return $category->getId();
            }, $categories
        );

        return $this->getFromDataMapper('getByCategoryIds', [$categoryIds]);
    }
}
