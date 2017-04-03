<?php

namespace Project\Domain\Orm\DataMappers;

use Opulence\Orm\DataMappers\SqlDataMapper;
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

        $statement = $this->writeConnection->prepare(
            'INSERT INTO file_downloads (file_id, customer_id, downloaded_at) VALUES (:file_id, :customer_id, :downloaded_at)'
        );
        $statement->bindValues(
            [
                'file_id'       => [$entity->getFile()->getId(), \PDO::PARAM_INT],
                'customer_id'   => [$entity->getCustomer()->getId(), \PDO::PARAM_INT],
                'downloaded_at' => [$entity->getDownloadedAt()->format(Entity::DATE_FORMAT), \PDO::PARAM_STR],
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
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Customer entity.');
        }

        $statement = $this->writeConnection->prepare(
            'DELETE FROM file_downloads WHERE id = :id'
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

        $sqlParts[] = 'SELECT file_downloads.id, file_downloads.file_id, file_downloads.customer_id, file_downloads.downloaded_at,';
        $sqlParts[] = '  files.file AS file_name,';
        $sqlParts[] = '  customers.name AS customer_name';
        $sqlParts[] = 'FROM file_downloads';
        $sqlParts[] = 'INNER JOIN files ON files.id=file_downloads.file_id';
        $sqlParts[] = 'INNER JOIN customers ON customers.id=file_downloads.customer_id';
        $sqlParts[] = 'WHERE';
        $sqlParts[] = implode(', ', $where);

        return implode(' ', $sqlParts);
    }

    /**
     * @param int|string $id
     *
     * @return array|null
     */
    public function getById($id)
    {
        $sql        = $this->getQuery(['file_downloads.id = :id']);
        $parameters = [
            'id' => [$id, \PDO::PARAM_INT],
        ];

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param int $customerId
     *
     * @return array|null
     */
    public function getByCustomerId(int $customerId)
    {
        $sql        = $this->getQuery(['customer_id = :customer_id']);
        $parameters = [
            'customer_id' => [$customerId, \PDO::PARAM_INT],
        ];

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY);
    }

    /**
     * @param int $fileId
     *
     * @return array|null
     */
    public function getByFileId(int $fileId)
    {
        $sql        = $this->getQuery(['file_id = :file_id']);
        $parameters = [
            'file_id' => [$fileId, \PDO::PARAM_INT],
        ];

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
            'UPDATE file_downloads SET file_id = :file_id, customer_id = :customer_id, downloaded_at = :downloaded_at WHERE id = :id'
        );
        $statement->bindValues(
            [
                'file_id'       => [$entity->getFile()->getId(), \PDO::PARAM_INT],
                'customer_id'   => [$entity->getCustomer()->getId(), \PDO::PARAM_INT],
                'downloaded_at' => [$entity->getDownloadedAt()->format(Entity::DATE_FORMAT), \PDO::PARAM_STR],
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
        $file     = new File(0, $hash['file_name'], '');
        $customer = new Customer(0, $hash['customer_name'], '', [], '', 0);

        return new Entity(
            (int)$hash['id'],
            $file,
            $customer,
            new \DateTime((string)$hash['downloaded_at'])
        );
    }
}
