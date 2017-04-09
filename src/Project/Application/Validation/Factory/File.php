<?php

namespace Project\Application\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class File extends ValidatorFactory
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
            ->field('description')
            ->required();

        $validator
            ->field('file')
            ->required();

        $validator
            ->field('category')
            ->integer()
            ->required();

        return $validator;
    }
}