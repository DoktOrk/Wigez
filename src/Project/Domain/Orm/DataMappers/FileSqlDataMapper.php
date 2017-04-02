<?php

namespace Project\Domain\Orm\DataMappers;

use Opulence\Orm\DataMappers\SqlDataMapper;
use Project\Domain\Entities\Category;
use Project\Domain\Entities\File as Entity;

class FileSqlDataMapper extends SqlDataMapper implements IFileDataMapper
{
    /**
     * @param Entity $entity
     */
    public function add($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Customer entity.');
        }

        $statement = $this->writeConnection->prepare(
            'INSERT INTO files (`file`, description, category_id, uploadedAt) VALUES (:name, :pos)'
        );
        $statement->bindValues(
            [
                'file'        => $entity->getFile(),
                'description' => $entity->getDescription(),
                'category_id' => $entity->getUploadedAt()->format(Entity::DATE_FORMAT),
            ]
        );
        $statement->execute();
    }

    /**
     * @param Entity $entity
     */
    public function delete($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Customer entity.');
        }

        $statement = $this->writeConnection->prepare(
            'DELETE FROM files WHERE id = :id'
        );
        $statement->bindValues(
            [
                'id' => [$entity->getId(), \PDO::PARAM_INT],
            ]
        );
        $statement->execute();
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $sql = $this->getQuery();

        // The last parameter says that we want a list of entities
        return $this->read($sql, [], self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param array $where
     *
     * @return string
     */
    private function getQuery(array $where = [1])
    {
        $sqlParts = [];

        $sqlParts[] = 'SELECT files.id, files.`file`, files.description, files.category_id, files.uploaded_at,';
        $sqlParts[] = '  categories.`name`';
        $sqlParts[] = 'FROM files';
        $sqlParts[] = 'INNER JOIN categories ON categories.id = files.category_id';
        $sqlParts[] = 'WHERE';
        $sqlParts[] = implode(', ', $where);
        $sqlParts[] = 'GROUP BY files.id';

        return implode(' ', $sqlParts);
    }

    /**
     * @param int|string $id
     *
     * @return array|null
     */
    public function getById($id)
    {
        $sql        = $this->getQuery(['`id` = :id']);
        $parameters = [
            'id' => [$id, \PDO::PARAM_INT],
        ];

        // The second-to-last parameter says that we want a single entity
        // The last parameter says that we expect one and only one entity
        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $name
     *
     * @return array|null
     */
    public function getByName(string $name)
    {
        $parameters = ['name' => $name];
        $sql        = $this->getQuery(['`name` = :name']);

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param Entity $entity
     */
    public function update($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Customer entity.');
        }

        $statement = $this->writeConnection->prepare(
            'UPDATE files SET `file` = :file, description = :description, uploaded_at = :uploaded_at, category_id = :category_id WHERE id = :id'
        );
        $statement->bindValues(
            [
                'file'        => $entity->getFile(),
                'description' => $entity->getDescription(),
                'uploaded_at' => $entity->getUploadedAt()->format(Entity::DATE_FORMAT),
                'category_id' => $entity->getCategory()->getId(),
                'id'          => [$entity->getId(), \PDO::PARAM_INT],
            ]
        );
        $statement->execute();
    }

    /**
     * @param array $hash
     *
     * @return Entity
     */
    protected function loadEntity(array $hash)
    {
        $category = new Category($hash['category_id'], $hash['name']);

        return new Entity(
            (int)$hash['id'],
            $hash['file'],
            $hash['description'],
            $category,
            new \DateTime($hash['uploaded_at'])
        );
    }
}
