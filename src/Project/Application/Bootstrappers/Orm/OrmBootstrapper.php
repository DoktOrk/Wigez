<?php

namespace Project\Application\Bootstrappers\Orm;

use Foo\Pdo\Statement\IPreprocessor;
use Foo\Pdo\Statement\Preprocessor\Factory;
use Opulence\Databases\ConnectionPools\ConnectionPool;
use Opulence\Databases\IConnection;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Ioc\IocException;
use Opulence\Orm\ChangeTracking\ChangeTracker;
use Opulence\Orm\ChangeTracking\IChangeTracker;
use Opulence\Orm\EntityRegistry;
use Opulence\Orm\Ids\Accessors\IdAccessorRegistry;
use Opulence\Orm\Ids\Accessors\IIdAccessorRegistry;
use Opulence\Orm\Ids\Generators\IdGeneratorRegistry;
use Opulence\Orm\Ids\Generators\IIdGeneratorRegistry;
use Opulence\Orm\IUnitOfWork;
use Opulence\Orm\UnitOfWork;
use Project\Domain\Entities\Category;
use Project\Domain\Entities\Customer;
use Project\Domain\Entities\Download;
use Project\Domain\Entities\File;
use Project\Domain\Entities\Page;
use Project\Domain\Entities\User;
use Project\Infrastructure\Orm\CategoryRepo;
use Project\Infrastructure\Orm\CustomerRepo;
use Project\Infrastructure\Orm\DataMappers\CategorySqlDataMapper;
use Project\Infrastructure\Orm\DataMappers\CustomerSqlDataMapper;
use Project\Infrastructure\Orm\DataMappers\DownloadSqlDataMapper;
use Project\Infrastructure\Orm\DataMappers\FileSqlDataMapper;
use Project\Infrastructure\Orm\DataMappers\PageSqlDataMapper;
use Project\Infrastructure\Orm\DataMappers\UserSqlDataMapper;
use Project\Infrastructure\Orm\DownloadRepo;
use Project\Infrastructure\Orm\FileRepo;
use Project\Infrastructure\Orm\PageRepo;
use Project\Infrastructure\Orm\UserRepo;
use RuntimeException;

/**
 * Defines the ORM bootstrapper
 */
class OrmBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /** @var array */
    protected $repoMappers = [
        CategoryRepo::class => [CategorySqlDataMapper::class, Category::class],
        CustomerRepo::class => [CustomerSqlDataMapper::class, Customer::class],
        PageRepo::class     => [PageSqlDataMapper::class, Page::class],
        FileRepo::class     => [FileSqlDataMapper::class, File::class],
        DownloadRepo::class => [DownloadSqlDataMapper::class, Download::class],
        UserRepo::class     => [UserSqlDataMapper::class, User::class],
    ];

    /**
     * @inheritdoc
     */
    public function getBindings(): array
    {
        $baseBindings = [
            IChangeTracker::class,
            IIdAccessorRegistry::class,
            IIdGeneratorRegistry::class,
            IUnitOfWork::class,
        ];

        return array_merge($baseBindings, array_keys($this->repoMappers));
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        try {
            $idAccessorRegistry  = new IdAccessorRegistry();
            $idGeneratorRegistry = new IdGeneratorRegistry();
            $this->registerIdAccessors($idAccessorRegistry);
            $this->registerIdGenerators($idGeneratorRegistry);
            $changeTracker  = new ChangeTracker();
            $entityRegistry = new EntityRegistry($idAccessorRegistry, $changeTracker);
            $unitOfWork     = new UnitOfWork(
                $entityRegistry,
                $idAccessorRegistry,
                $idGeneratorRegistry,
                $changeTracker,
                $container->resolve(IConnection::class)
            );
            $container->bindFactory(IPreprocessor::class, [Factory::class, 'getPreprocessor']);
            $this->bindRepositories($container, $unitOfWork);
            $container->bindInstance(IIdAccessorRegistry::class, $idAccessorRegistry);
            $container->bindInstance(IIdGeneratorRegistry::class, $idGeneratorRegistry);
            $container->bindInstance(IChangeTracker::class, $changeTracker);
            $container->bindInstance(IUnitOfWork::class, $unitOfWork);
        } catch (IocException $ex) {
            throw new RuntimeException('Failed to register ORM bindings', 0, $ex);
        }
    }

    /**
     * Registers Id getters/setters for classes managed by the unit of work
     *
     * @param IIdAccessorRegistry $idAccessorRegistry The Id accessor registry
     */
    private function registerIdAccessors(IIdAccessorRegistry $idAccessorRegistry)
    {
        // Register your Id getters/setters for classes that will be managed by the unit of work
    }

    /**
     * Registers Id generators for classes managed by the unit of work
     *
     * @param IIdGeneratorRegistry $idGeneratorRegistry The Id generator registry
     */
    private function registerIdGenerators(IIdGeneratorRegistry $idGeneratorRegistry)
    {
        // Register your Id generators for classes that will be managed by the unit of work
    }

    /**
     * Binds repositories to the container
     *
     * @param IContainer  $container  The container to bind to
     * @param IUnitOfWork $unitOfWork The unit of work to use in repositories
     */
    private function bindRepositories(IContainer $container, IUnitOfWork $unitOfWork)
    {
        $connectionPool = $container->resolve(ConnectionPool::class);
        $preprocessor   = Factory::getPreprocessor();

        $readConnection  = $connectionPool->getReadConnection();
        $writeConnection = $connectionPool->getWriteConnection();

        foreach ($this->repoMappers as $repoClass => $classes) {
            $container->bindFactory(
                $repoClass,
                $this->createFactory(
                    $container,
                    $repoClass,
                    $classes[0],
                    $classes[1],
                    $readConnection,
                    $writeConnection,
                    $preprocessor,
                    $unitOfWork
                )
            );
        }
    }

    /**
     * @param IContainer    $container
     * @param string        $repoClass
     * @param string        $dataMapperClass
     * @param string        $entityClass
     * @param IConnection   $readConnection
     * @param IConnection   $writeConnection
     * @param IPreprocessor $preprocessor
     * @param IUnitOfWork   $unitOfWork
     *
     * @return \Closure
     */
    private function createFactory(
        IContainer $container,
        string $repoClass,
        string $dataMapperClass,
        string $entityClass,
        IConnection $readConnection,
        IConnection $writeConnection,
        IPreprocessor $preprocessor,
        IUnitOfWork $unitOfWork
    ) {
        return function () use (
            $container,
            $repoClass,
            $dataMapperClass,
            $entityClass,
            $readConnection,
            $writeConnection,
            $preprocessor,
            $unitOfWork
        ) {
            $dataMapper = new $dataMapperClass($readConnection, $writeConnection, $preprocessor);

            return new $repoClass($entityClass, $dataMapper, $unitOfWork);
        };
    }
}
