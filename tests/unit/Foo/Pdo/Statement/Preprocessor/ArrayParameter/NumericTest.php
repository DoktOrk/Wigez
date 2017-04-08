<?php

declare(strict_types=1);

namespace Foo\Pdo\Statement\Preprocessor\ArrayParameter;

class NumericTest extends \PHPUnit\Framework\TestCase
{
    /** @var Numeric */
    protected $sut;

    public function setUp()
    {
        $this->sut = new Numeric();
    }

    /**
     * @return array
     */
    public function processDataProvider()
    {
        return [
            'missing-where-in-parameters' => [
                'SELECT * FROM foo',
                [],
                [],
                'SELECT * FROM foo',
                [],
                false,
            ],
            'associative-parameters'      => [
                'SELECT * FROM foo',
                [],
                [
                    'greeting' => 'hello',
                    'pass'     => 'iddqd',
                ],
                'SELECT * FROM foo',
                [],
                false,
            ],
            'simple-partials'             => [
                'SELECT * FROM foo WHERE enum_values IN ? AND bar=? AND foo=? AND id IN(?) AND baz = ?',
                [
                    ['a', 'b', 'c', 'd'],
                    'baz',
                    'baz',
                    [1, 2, 3],
                    'foo',
                ],
                [
                    0 => ['a', 'b', 'c', 'd'],
                    3 => [1, 2, 3],
                ],
                'SELECT * FROM foo WHERE enum_values IN ?, ?, ?, ? AND bar=? AND foo=? AND id IN(?, ?, ?) AND baz = ?',
                [
                    'a',
                    'b',
                    'c',
                    'd',
                    'baz',
                    'baz',
                    1,
                    2,
                    3,
                    'foo',
                ],
                true,
            ],
        ];
    }

    /**
     * @dataProvider processDataProvider
     *
     * @param string $origQuery
     * @param array  $origParameters
     * @param array  $whereInParameters
     * @param string $resultQuery
     * @param array  $resultParameters
     * @param bool   $result
     */
    public function testProcess(
        string $origQuery,
        array $origParameters,
        array $whereInParameters,
        string $expectedQuery,
        array $expectedParameters,
        bool $expectedResult
    ) {
        $actualResult = $this->sut->process($origQuery, $origParameters, $whereInParameters);

        $this->assertSame($expectedQuery, $origQuery);
        $this->assertSame($expectedParameters, $origParameters);
        $this->assertSame($expectedResult, $actualResult);
    }
}
