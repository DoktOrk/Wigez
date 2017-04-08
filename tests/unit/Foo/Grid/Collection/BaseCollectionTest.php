<?php

namespace Foo\Grid\Collection;

class BaseCollectionTest extends \PHPUnit\Framework\TestCase
{
    public function testToStringContainsList()
    {
        $sut = new BaseCollection();

        $sut[] = 'A';
        $sut[] = 'B';

        $this->assertContains('A', (string)$sut);
        $this->assertContains('B', (string)$sut);
    }

    public function testToStringCanWrapList()
    {
        $sut = new BaseCollection('A', ['foo' => 'baz']);

        $sut[] = 'B';
        $sut[] = 'C';

        $this->assertSame("<A foo=\"baz\">B\nC</A>", (string)$sut);
    }
}

