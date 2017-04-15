<?php

namespace Project\Application\Console\Commands;

use Opulence\Console\Commands\Command;
use Opulence\Console\Responses\IResponse;
use Opulence\Orm\IUnitOfWork;
use Project\Domain\Entities\Customer;
use Project\Infrastructure\Orm\CustomerRepo;

/**
 * Defines an example "Hello, world" command
 */
class CustomerHashPasswordsCommand extends Command
{
    /** @var CustomerRepo */
    protected $customerRepo;

    /** @var IUnitOfWork */
    protected $unitOfWork;

    /**
     * CustomerHashPasswordsCommand constructor.
     *
     * @param CustomerRepo $customerRepo
     * @param IUnitOfWork  $unitOfWork
     */
    public function __construct(CustomerRepo $customerRepo, IUnitOfWork $unitOfWork)
    {
        $this->customerRepo = $customerRepo;
        $this->unitOfWork   = $unitOfWork;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function define()
    {
        $this->setName('customer:hash-password')
            ->setDescription('Hashes plain-text passwords of customers');
    }

    /**
     * @inheritdoc
     */
    protected function doExecute(IResponse $response)
    {
        /** @var Customer $customer */
        foreach ($this->customerRepo->getAll() as $customer) {
            $hashedPassword = \password_hash($customer->getPassword(), PASSWORD_DEFAULT);
            $message        = sprintf(
                'Updating password for customer "%s": "%s" -> "%s"',
                $customer->getName(),
                $customer->getPassword(),
                $hashedPassword
            );

            $customer->setPassword($hashedPassword);

            $response->writeln($message);
        }

        $this->unitOfWork->commit();
        $response->writeln('All work is done.');
    }
}
