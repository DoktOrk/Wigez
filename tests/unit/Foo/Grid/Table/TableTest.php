<?php

namespace Foo\Grid\Table;

use Foo\Grid\Collection\Cells;
use Foo\Grid\Collection\Rows;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    /** @var Table */
    protected $sut;

    /** @var Rows|MockObject */
    protected $rows;

    /** @var Cells|MockObject */
    protected $header;

    public function setUp()
    {
        $methods = ['__toString', 'setIndentation'];

        $this->rows = $this->getMockBuilder(Rows::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();

        $this->header = $this->getMockBuilder(Cells::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();

        $this->sut = new Table($this->rows, $this->header);
    }

    public function testToStringContainsHeaders()
    {
        $this->rows->method('__toString')->willReturn('A');
        $this->header->method('__toString')->willReturn('B');

        $this->assertContains((string)$this->header, (string)$this->sut);
    }

    public function testToStringContainsRows()
    {
        $this->rows->method('__toString')->willReturn('A');
        $this->header->method('__toString')->willReturn('B');

        $this->assertContains((string)$this->rows, (string)$this->sut);
    }
}

