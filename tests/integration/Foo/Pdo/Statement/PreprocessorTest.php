<?php

namespace Integration\Foo\Pdo\Statement;

use Foo\Pdo\Statement\Preprocessor;
use Foo\Pdo\Statement\Preprocessor\ArrayParameter;
use Foo\Pdo\Statement\Preprocessor\Factory;

class PreprocessorTest extends \PHPUnit\Framework\TestCase
{
    /** @var Preprocessor */
    protected $sut;

    public function setUp()
    {
        $this->sut = Factory::getPreprocessor();
    }

    public function testArrayParameterWorks()
    {
        $query = 'SELECT * FROM foo WHERE foo = ? AND bar IN (?) AND baz IN (:baz)';
        $parameters = [
            [1, \PDO::PARAM_STR],
            [[2, 3, 4], ArrayParameter::PARAM_STR_ARRAY],
            'baz' => [[5, 6, 7, 8], ArrayParameter::PARAM_INT_ARRAY]
        ];
        $expectedQuery = 'SELECT * FROM foo WHERE foo = ? AND bar IN (?, ?, ?) AND baz IN (:baz__expanded0, :baz__expanded1, :baz__expanded2, :baz__expanded3)';
        $expectedParameters = [
            [1, \PDO::PARAM_STR],
            [2, \PDO::PARAM_STR],
            [3, \PDO::PARAM_STR],
            [4, \PDO::PARAM_STR],
            'baz__expanded0' => [5, \PDO::PARAM_INT],
            'baz__expanded1' => [6, \PDO::PARAM_INT],
            'baz__expanded2' => [7, \PDO::PARAM_INT],
            'baz__expanded3' => [8, \PDO::PARAM_INT],
        ];

        $this->sut->process($query, $parameters);

        $this->assertSame($expectedQuery, $query);
        $this->assertSame($expectedParameters, $parameters);
    }
}