<?php

namespace Project\Domain\Orm\DataMappers;

use Foo\Pdo\Statement\Preprocessor\ArrayParameter;
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

        $values    = [
            'file'        => [$entity->getFile(), \PDO::PARAM_STR],
            'description' => [$entity->getDescription(), \PDO::PARAM_STR],
            'category_id' => [$entity->getCategory()->getId(), \PDO::PARAM_INT],
            'uploaded_at' => [$entity->getUploadedAt()->format(Entity::DATE_FORMAT), \PDO::PARAM_STR],
        ];
        $statement = $this->writeConnection->prepare(
            'INSERT INTO files (`file`, description, category_id, uploaded_at) VALUES (:file, :description, :category_id, :uploaded_at)'
        );
        $statement->bindValues($values);
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

        $values = [
            'id' => [$entity->getId(), \PDO::PARAM_INT],
        ];

        $statement = $this->writeConnection->prepare(
            'UPDATE files SET deleted = 1 WHERE id = :id'
        );
        $statement->bindValues($values);
        $statement->execute();
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $sql = $this->getQuery();

        return $this->read($sql, [], self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param int|string $id
     *
     * @return array|null
     */
    public function getById($id)
    {
        $sql        = $this->getQuery(['files.id = :id']);
        $parameters = [
            'id' => [$id, \PDO::PARAM_INT],
        ];

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param array $categoryIds
     *
     * @return array|null
     */
    public function getByCategoryIds(array $categoryIds)
    {
        $sql         = $this->getQuery(['categories.id IN (:category_ids)']);
        $parameters  = [
            'category_ids' => [$categoryIds, ArrayParameter::PARAM_INT_ARRAY],
        ];

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

        $values    = [
            'file'        => [$entity->getFile(), \PDO::PARAM_STR],
            'description' => [$entity->getDescription(), \PDO::PARAM_STR],
            'uploaded_at' => [$entity->getUploadedAt()->format(Entity::DATE_FORMAT), \PDO::PARAM_STR],
            'category_id' => [$entity->getCategory()->getId(), \PDO::PARAM_INT],
            'id'          => [$entity->getId(), \PDO::PARAM_INT],
        ];
        $statement = $this->writeConnection->prepare(
            'UPDATE files SET `file` = :file, description = :description, uploaded_at = :uploaded_at, category_id = :category_id WHERE id = :id'
        );
        $statement->bindValues($values);
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
     * @param array $where
     *
     * @return string
     */
    private function getQuery(array $where = [1])
    {
        $sqlParts = [];

        $sqlParts[] = 'SELECT files.id, files.`file`, files.description, files.uploaded_at,';
        $sqlParts[] = '  categories.`name`, categories.id AS category_id';
        $sqlParts[] = 'FROM files';
        $sqlParts[] = 'LEFT JOIN categories ON categories.id = files.category_id AND categories.deleted = 0';
        $sqlParts[] = 'WHERE';
        $sqlParts[] = implode(', ', $where);
        $sqlParts[] = '  AND files.deleted = 0';
        $sqlParts[] = 'GROUP BY files.id';

        return implode(' ', $sqlParts);
    }
}
