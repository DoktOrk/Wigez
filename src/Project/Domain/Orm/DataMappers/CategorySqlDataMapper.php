<?php

namespace Project\Domain\Orm\DataMappers;

use Opulence\Orm\DataMappers\SqlDataMapper;
use Project\Domain\Entities\Category as Entity;

class CategorySqlDataMapper extends SqlDataMapper implements ICategoryDataMapper
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
            'INSERT INTO categories (`name`, pos) VALUES (:name, :pos)'
        );
        $statement->bindValues(
            [
                'name' => $entity->getName(),
                'pos'  => $entity->getPos(),
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
            'DELETE FROM categories WHERE id = :id'
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

        $sqlParts[] = 'SELECT categories.id, categories.`name`, categories.pos';
        $sqlParts[] = 'FROM categories';
        $sqlParts[] = 'WHERE';
        $sqlParts[] = implode(', ', $where);
        $sqlParts[] = 'GROUP BY categories.id';

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
            'UPDATE categories SET `name` = :name, pos = :pos WHERE id = :id'
        );
        $statement->bindValues(
            [
                'name' => $entity->getName(),
                'pos'  => $entity->getPos(),
                'id'   => [$entity->getId(), \PDO::PARAM_INT],
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
        return new Entity(
            (int)$hash['id'],
            $hash['name'],
            $hash['pos']
        );
    }
}
