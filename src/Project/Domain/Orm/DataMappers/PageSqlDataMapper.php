<?php

namespace Project\Domain\Orm\DataMappers;

use Opulence\Orm\DataMappers\SqlDataMapper;
use Project\Domain\Entities\Page as Entity;

class PageSqlDataMapper extends SqlDataMapper implements IPageDataMapper
{
    /**
     * @param Entity $entity
     */
    public function add($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Page entity.');
        }

        $statement = $this->writeConnection->prepare(
            'INSERT INTO pages (title, body) VALUES (:title, :body)'
        );
        $statement->bindValues(
            [
                'title' => $entity->getTitle(),
                'body'  => $entity->getBody(),
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
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Page entity.');
        }

        $statement = $this->writeConnection->prepare(
            'DELETE FROM pages WHERE id = :id'
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
        $sql = 'SELECT id, title, body FROM pages';

        // The last parameter says that we want a list of entities
        return $this->read($sql, [], self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param int|string $id
     *
     * @return array|null
     */
    public function getById($id)
    {
        $sql        = 'SELECT id, title, body FROM pages WHERE id = :id';
        $parameters = [
            'id' => [$id, \PDO::PARAM_INT],
        ];

        // The second-to-last parameter says that we want a single entity
        // The last parameter says that we expect one and only one entity
        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $title
     *
     * @return array|null
     */
    public function getByTitle(string $title)
    {
        $sql        = 'SELECT id, title, body FROM pages WHERE title = :title';
        $parameters = [
            'title' => $title,
        ];

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param Entity $entity
     */
    public function update($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Page entity.');
        }

        $statement = $this->writeConnection->prepare(
            'UPDATE pages SET title = :title, body = :body WHERE id = :id'
        );
        $statement->bindValues(
            [
                'title' => $entity->getTitle(),
                'body'  => $entity->getBody(),
                'id'    => [$entity->getId(), \PDO::PARAM_INT],
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
            $hash['title'],
            $hash['body']
        );
    }
}
