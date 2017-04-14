<?php

namespace Project\Infrastructure\Orm\DataMappers;

use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;
use Project\Domain\Entities\Customer;
use Project\Domain\Entities\Download as Entity;
use Project\Domain\Entities\File;

class DownloadSqlDataMapper extends SqlDataMapper implements IDownloadDataMapper
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
                'file_downloads',
                [
                    'file_id'       => [$entity->getFile()->getId(), \PDO::PARAM_INT],
                    'customer_id'   => [$entity->getCustomer()->getId(), \PDO::PARAM_INT],
                    'downloaded_at' => [$entity->getDownloadedAt()->format(Entity::DATE_FORMAT), \PDO::PARAM_STR],
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

        $query = (new QueryBuilder)->delete('file_downloads')
            ->where('id = :id')
            ->addNamedPlaceholderValue('id', $entity->getId(), \PDO::PARAM_INT);

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
        $query = $this->getBaseQuery()->andWhere('file_downloads.id = :id');

        $parameters = [
            'id' => [$id, \PDO::PARAM_INT],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param int $customerId
     *
     * @return array|null
     */
    public function getByCustomerId(int $customerId)
    {
        $query      = $this->getBaseQuery()->andWhere('customer_id = :customer_id');
        $parameters = [
            'customer_id' => [$customerId, \PDO::PARAM_INT],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param int $fileId
     *
     * @return array|null
     */
    public function getByFileId(int $fileId)
    {
        $query = $this->getBaseQuery()->andWhere('file_id = :file_id');

        $parameters = [
            'file_id' => [$fileId, \PDO::PARAM_INT],
        ];

        return $this->read($query->getSql(), $parameters, self::VALUE_TYPE_ENTITY);
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
                'file_downloads',
                'file_downloads',
                [
                    'file_id'       => [$entity->getFile()->getId(), \PDO::PARAM_INT],
                    'customer_id'   => [$entity->getCustomer()->getId(), \PDO::PARAM_INT],
                    'downloaded_at' => [$entity->getDownloadedAt()->format(Entity::DATE_FORMAT), \PDO::PARAM_STR],
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
        $file     = new File(0, $hash['file'], $hash['filename'], '');
        $customer = new Customer(0, $hash['customer_name'], '', [], '', 0);

        return new Entity(
            (int)$hash['id'],
            $file,
            $customer,
            new \DateTime((string)$hash['downloaded_at'])
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
                'file_downloads.id',
                'file_downloads.file_id',
                'file_downloads.customer_id',
                'file_downloads.downloaded_at',
                'files.file AS file',
                'files.filename AS filename',
                'customers.name AS customer_name'
            )
            ->from('file_downloads')
            ->innerJoin(
                'files',
                'files',
                'files.id=file_downloads.file_id'
            )
            ->innerJoin(
                'customers',
                'customers',
                'customers.id=file_downloads.customer_id'
            )
            ->where('1');

        return $query;
    }
}
