<?php

namespace Project\Application\Auth;

use Project\Domain\Entities\Customer;
use Project\Domain\Entities\User;
use Project\Infrastructure\Orm\CustomerRepo;
use Project\Infrastructure\Orm\UserRepo;

class Authenticator
{
    /** @var UserRepo */
    protected $userRepo;

    /** @var CustomerRepo */
    protected $customerRepo;

    /**
     * Authenticator constructor.
     *
     * @param UserRepo     $userRepo
     * @param CustomerRepo $customerRepo
     */
    public function __construct(UserRepo $userRepo, CustomerRepo $customerRepo)
    {
        $this->userRepo     = $userRepo;
        $this->customerRepo = $customerRepo;
    }

    /**
     * @param string $password
     * @param string $storedPassword
     *
     * @return bool
     */
    public function canLogin(string $password, string $storedPassword): bool
    {
        $verified = \password_verify($password, $storedPassword);

        return $verified;
    }

    /**
     * @param string $identifier
     *
     * @return string
     */
    public function getUserPassword(string $identifier): string
    {
        /** @var User $entity */
        $entity = $this->userRepo->find($identifier);

        return $entity ? $entity->getPassword() : '';
    }

    /**
     * @param string $identifier
     *
     * @return string
     */
    public function getCustomerPassword(string $identifier): string
    {
        /** @var Customer $entity */
        $entity = $this->customerRepo->find($identifier);

        return $entity ? $entity->getPassword() : '';
    }
}

