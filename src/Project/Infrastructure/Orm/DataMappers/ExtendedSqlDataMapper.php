<?php

namespace Project\Infrastructure\Orm\DataMappers;

use Foo\Pdo\Statement\IPreprocessor;
use Opulence\Databases\IConnection;
use Opulence\Orm\DataMappers\SqlDataMapper;

abstract class ExtendedSqlDataMapper extends SqlDataMapper
{
    /** @var IConnection The read connection */
    protected $readConnection = null;

    /** @var IConnection The write connection */
    protected $writeConnection = null;

    /** @var IPreprocessor */
    protected $preprocessor = null;

    /**
     * @param IConnection   $readConnection  The read connection
     * @param IConnection   $writeConnection The write connection
     * @param IPreprocessor $preprocessor    Query preprocessor
     */
    public function __construct(IConnection $readConnection, IConnection $writeConnection, IPreprocessor $preprocessor)
    {
        $this->readConnection  = $readConnection;
        $this->writeConnection = $writeConnection;
        $this->preprocessor    = $preprocessor;
    }
}