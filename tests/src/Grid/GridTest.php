<?php

namespace Grid;

use Grid\Collection\Filters;
use Grid\Table\Table;

class GridTest extends \PHPUnit_Framework_TestCase
{
    public function testToStringContainsTable()
    {
        $table = $this->getMockBuilder(Table::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        $table->method('__toString')->willReturn('ABC');

        $sut = new Grid($table);

        $this->assertContains('ABC', (string)$sut);
    }

    public function testToStringContainsFilters()
    {
        $table = $this->getMockBuilder(Table::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        $table->method('__toString')->willReturn('ABC');

        $sut = new Grid($table);

        $this->assertContains('ABC', (string)$sut);
    }

    public function testToStringContainsActions()
    {
        $table = $this->getMockBuilder(Table::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        $table->method('__toString')->willReturn('ABC');

        $sut = new Grid($table);

        $this->assertContains('ABC', (string)$sut);
    }

    public function testToStringCanWrapContentInForm()
    {
        $table = $this->getMockBuilder(Table::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        $filters = $this->getMockBuilder(Filters::class)
            ->disableOriginalConstructor()
            ->setMethods(['__toString'])
            ->getMock();

        $table->method('__toString')->willReturn('A');
        $filters->method('__toString')->willReturn('B');

        $sut = new Grid($table, $filters);

        $this->assertContains(Grid::TAG_FORM, (string)$sut);
    }
}

