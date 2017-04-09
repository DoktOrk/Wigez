<?php

namespace Project\Infrastructure\Orm\DataMappers;

use Foo\Pdo\Statement\Preprocessor\ArrayParameter;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;
use Project\Domain\Entities\Category;
use Project\Domain\Entities\File as Entity;

class FileSqlDataMapper extends ExtendedSqlDataMapper implements IFileDataMapper
{
    /**
     * @param Entity $entity
     */
    public function add($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Customer entity.');
        }

        $query = (new QueryBuilder())
            ->insert(
                'files',
                [
                    'file'        => [$entity->getFile(), \PDO::PARAM_STR],
                    'description' => [$entity->getDescription(), \PDO::PARAM_STR],
                    'category_id' => [$entity->getCategory()->getId(), \PDO::PARAM_INT],
                    'uploaded_at' => [$entity->getUploadedAt()->format(Entity::DATE_FORMAT), \PDO::PARAM_STR],
                ]
            );

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();

        $entity->setId($this->writeConnection->lastInsertId());
    }

    /**
     * @param Entity $entity
     */
    public function delete($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Customer entity.');
        }

        $query = (new QueryBuilder())
            ->update('files', 'files', ['deleted' => [1, \PDO::PARAM_INT]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_INT);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $query = $this->getBaseQuery();

        return $this->read($query->getSql(), [], self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param int|string $id
     *
     * @return array|null
     */
    public function getById($id)
    {
        $query = $this->getBaseQuery()->andWhere('files.id = :file_id');

        $parameters = [
            'file_id' => [$id, \PDO::PARAM_INT],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param array $categoryIds
     *
     * @return array|null
     */
    public function getByCategoryIds(array $categoryIds)
    {
        $query = $this->getBaseQuery()->andWhere('categories.id IN (:category_ids)');

        $parameters = [
            'category_ids' => [$categoryIds, ArrayParameter::PARAM_INT_ARRAY],
        ];
        $sql        = $query->getSql();

        $this->preprocessor->process($sql, $parameters);

        return $this->read($sql, $parameters, self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param Entity $entity
     */
    public function update($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Customer entity.');
        }

        $query = (new QueryBuilder())
            ->update(
                'files',
                'files',
                [
                    'file'        => [$entity->getFile(), \PDO::PARAM_STR],
                    'description' => [$entity->getDescription(), \PDO::PARAM_STR],
                    'uploaded_at' => [$entity->getUploadedAt()->format(Entity::DATE_FORMAT), \PDO::PARAM_STR],
                    'category_id' => [$entity->getCategory()->getId(), \PDO::PARAM_INT],
                ]
            )
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_INT);

        $statement = $this->writeConnection->prepare($query->getSql());
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @param array $hash
     *
     * @return Entity
     */
    protected function loadEntity(array $hash)
    {
        $category = new Category((int)$hash['category_id'], (string)$hash['name']);

        return new Entity(
            (int)$hash['id'],
            $hash['file'],
            $hash['description'],
            $category,
            new \DateTime($hash['uploaded_at'])
        );
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery()
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'files.id',
                'files.file',
                'files.description',
                'files.uploaded_at',
                'categories.name',
                'categories.id AS category_id'
            )
            ->from('files')
            ->leftJoin(
                'categories',
                'categories',
                'categories.id = files.category_id AND categories.deleted =0'
            )
            ->where('files.deleted = 0')
            ->groupBy('files.id');

        return $query;
    }
}
