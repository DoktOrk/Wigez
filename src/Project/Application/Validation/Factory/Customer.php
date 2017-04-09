<?php

namespace Project\Application\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class Customer extends ValidatorFactory
{
    /**
     * @return IValidator
     */
    public function createValidator(): IValidator
    {
        $validator = parent::createValidator();

        $validator
            ->field('id')
            ->integer();

        $validator
            ->field('name')
            ->required();

        $validator
            ->field('email')
            ->email()
            ->required();

        $validator
            ->field('categories');

        $validator
            ->field('password');

        $validator
            ->field('password_confirmed')
            ->equalsField('password');

        return $validator;
    }
}