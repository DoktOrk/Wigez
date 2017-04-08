<?php

namespace Foo\Grid\Pagination;

use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{
    /**
     * @return array
     */
    public function getToDataProvider()
    {
        return [
            [1, 10, 10, 5, 10],
            [11, 10, 23, 5, 20],
            [16, 5, 3223, 5, 20],
        ];
    }

    /**
     * @dataProvider getToDataProvider
     *
     * @param int $from
     * @param int $pageSize
     * @param int $totalCount
     * @param int $numberCount
     * @param int $expectedResult
     */
    public function testGetTo($from, $pageSize, $totalCount, $numberCount, $expectedResult)
    {
        $sut = new Pagination($from, $pageSize, $totalCount, $numberCount);

        $actualResult = $sut->getTo();

        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function getMinPageNumberDataProvider()
    {
        return [
            // currentPage: floor(1 / 10)     -> 0
            // minPage:     0 - floor(5 / 2)  -> -3
            // result:      max(-3, 0) + 1    -> 1
            [1, 10, 10, 5, 1],

            // currentPage: floor(141 / 20)   -> 7
            // minPage:     7 - floor(6 / 2)  -> 4
            // result:      max(4, 0) + 1     -> 5
            [141, 20, 942, 6, 5],

            // currentPage: floor(1678 / 20)  -> 83
            // minPage:     83 - floor(9 / 2) -> 79
            // result:      max(79, 0) + 1    -> 80
            [1678, 20, 3223, 9, 80],
        ];
    }

    /**
     * @dataProvider getMinPageNumberDataProvider
     *
     * @param int $from
     * @param int $pageSize
     * @param int $totalCount
     * @param int $numberCount
     * @param int $expectedResult
     */
    public function testGetMinPageNumber($from, $pageSize, $totalCount, $numberCount, $expectedResult)
    {
        $sut = new Pagination($from, $pageSize, $totalCount, $numberCount);

        $actualResult = $sut->getMinPageNumber();

        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function getMaxPageNumberDataProvider()
    {
        return [
            // currentPage: floor(1 / 10)          -> 0
            // maxPage:     0 + floor(5 / 2) + 1   -> 3
            // veryMax:     ceil(10 / 10)          -> 1
            // result:      min(3, 1)              -> 1
            [1, 10, 10, 5, 1],

            // currentPage: floor(141 / 20)        -> 7
            // maxPage:     7 + floor(6 / 2) + 1   -> 11
            // veryMax:     ceil(942 / 20)         -> 47
            // result:      min(11, 47)            -> 11
            [141, 20, 942, 6, 11],

            // currentPage: floor(1678 / 20)       -> 83
            // maxPage:     83 + floor(9 / 2) + 1  -> 88
            // veryMax:     ceil(3223 / 20)        -> 161
            // result:      min(88, 161)           -> 88
            [1678, 20, 3223, 9, 88],
        ];
    }

    /**
     * @dataProvider getMaxPageNumberDataProvider
     *
     * @param int $from
     * @param int $pageSize
     * @param int $totalCount
     * @param int $numberCount
     * @param int $expectedResult
     */
    public function testGetMaxPageNumber($from, $pageSize, $totalCount, $numberCount, $expectedResult)
    {
        $sut = new Pagination($from, $pageSize, $totalCount, $numberCount);

        $actualResult = $sut->getMaxPageNumber();

        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function getPageNumbersDataProvider()
    {
        return [
            [1, 10, 10, 5, [1]],
            [141, 20, 942, 6, [5, 6, 7, 8, 9, 10]],
            [1678, 20, 3223, 9, [80, 81, 82, 83, 84, 85, 86, 87, 88]],
        ];
    }

    /**
     * @dataProvider getPageNumbersDataProvider
     *
     * @param int $from
     * @param int $pageSize
     * @param int $totalCount
     * @param int $numberCount
     * @param array $expectedResult
     */
    public function testGetPageNumbers($from, $pageSize, $totalCount, $numberCount, $expectedResult)
    {
        $sut = new Pagination($from, $pageSize, $totalCount, $numberCount);

        $actualResult = $sut->getPageNumbers();

        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function getToStringDataProvider()
    {
        return [
            [1, 10, 10, 5, [1]],
            [141, 20, 942, 6, [5, 6, 7, 8, 9, 10]],
            [1678, 20, 3223, 9, [80, 81, 82, 83, 84, 85, 86, 87, 88]],
        ];
    }

    /**
     * @dataProvider getToStringDataProvider
     *
     * @param int $from
     * @param int $pageSize
     * @param int $totalCount
     * @param int $numberCount
     * @param array $expectedResult
     */
    public function testToString($from, $pageSize, $totalCount, $numberCount, $expectedResult)
    {
        $sut = new Pagination($from, $pageSize, $totalCount, $numberCount);

        $actualResult = $sut->__toString();

        foreach ($expectedResult as $number) {
            $this->assertContains("$number", $actualResult);
        }
    }
}