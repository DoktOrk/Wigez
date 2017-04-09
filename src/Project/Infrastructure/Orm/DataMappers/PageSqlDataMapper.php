<?php

namespace Project\Infrastructure\Orm\DataMappers;

use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;
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

        $query = (new QueryBuilder())
            ->insert(
                'pages',
                [
                    'title' => $entity->getTitle(),
                    'body'  => $entity->getBody(),
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
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Page entity.');
        }

        $query = (new QueryBuilder())
            ->update('pages', 'pages', ['deleted' => [1, \PDO::PARAM_INT]])
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
     * @return Entity|null
     */
    public function getById($id)
    {
        $query = $this->getBaseQuery()->andWhere('pages.id = :page_id');

        $parameters = [
            'page_id' => [$id, \PDO::PARAM_INT],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param string $title
     *
     * @return array|null
     */
    public function getByTitle(string $title)
    {
        $query = $this->getBaseQuery()->andWhere('title = :title');

        $parameters = [
            'title' => $title,
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param Entity $entity
     */
    public function update($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Page entity.');
        }

        $query = (new QueryBuilder())
            ->update(
                'pages',
                'pages',
                [
                    'title' => [$entity->getTitle(), \PDO::PARAM_STR],
                    'body'  => [$entity->getBody(), \PDO::PARAM_STR],
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
        return new Entity(
            (int)$hash['id'],
            $hash['title'],
            $hash['body']
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
                'pages.id',
                'pages.title',
                'pages.body'
            )
            ->from('pages')
            ->where('pages.deleted = 0');

        return $query;
    }
}
