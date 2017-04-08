<?php

declare(strict_types=1);

namespace Foo\Pdo\Statement\Preprocessor;

use Foo\Pdo\Statement\Preprocessor;
use Foo\Pdo\Statement\Preprocessor\ArrayParameter\Associative;
use Foo\Pdo\Statement\Preprocessor\ArrayParameter\Numeric;

class Factory
{
    /** @var Preprocessor */
    protected $instance;

    public function getInstance()
    {
        if ($this->instance instanceof Preprocessor) {
            return $this->instance;
        }

        $this->instance = new Preprocessor(new ArrayParameter(new Numeric(), new Associative()));

        return $this->instance;
    }
}
