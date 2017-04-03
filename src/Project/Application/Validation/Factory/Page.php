<?php

namespace Project\Application\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class Page extends ValidatorFactory
{
    /**
     * @return IValidator
     */
    public function createValidator(): IValidator
    {
        $validator = parent::createValidator();

        $validator
            ->field('id')
            ->integer()
        ;

        $validator
            ->field('title')
            ->required()
        ;

        $validator
            ->field('body')
        ;

        return $validator;
    }
}