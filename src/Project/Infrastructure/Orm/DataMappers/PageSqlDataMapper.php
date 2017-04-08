<?php

namespace Project\Infrastructure\Orm\DataMappers;

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

        $entity->setId($this->writeConnection->lastInsertId());
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
            'UPDATE pages SET deleted = 1 WHERE id = :id'
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

        $sqlParts[] = 'SELECT pages.id, pages.title, pages.body';
        $sqlParts[] = 'FROM pages';
        $sqlParts[] = 'WHERE';
        $sqlParts[] = implode(' AND ', $where);
        $sqlParts[] = 'AND pages.deleted = 0';

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

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $title
     *
     * @return array|null
     */
    public function getByTitle(string $title)
    {
        $sql        = $this->getQuery(['`title` = :title']);
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
