<?php

namespace Foo\Grid\Pagination;

use Foo\Grid\Action\Button;
use Foo\Grid\Collection\Actions;
use Foo\Grid\Component\Component;

class Pagination extends Component implements IPagination
{
    /** @var int */
    protected $from = 0;

    /** @var int */
    protected $to = 0;

    /** @var int */
    protected $pageSize = 0;

    /** @var int */
    protected $totalCount = 0;

    /** @var int */
    protected $numberCount = 5;

    /** @var array */
    protected $pageSizes = [10, 50, 200];

    /** @var array */
    protected $attributes = ['class' => 'hello'];

    /** @var Actions */
    protected $buttons;

    /**
     * @param int   $from
     * @param int   $pageSize
     * @param int   $totalCount
     * @param int   $maxNumButtons
     * @param array $pageSizes
     * @param array $attributes
     */
    public function __construct(
        $from,
        $pageSize,
        $totalCount,
        $numberCount = 5,
        $pageSizes = [10, 50, 200],
        $attributes = []
    ) {
        $this->from        = $from;
        $this->pageSize    = $pageSize;
        $this->totalCount  = $totalCount;
        $this->pageSizes   = $pageSizes;
        $this->numberCount = $numberCount;

        $this->to      = min($this->totalCount, $this->from + $this->pageSize - 1);
        $this->buttons = new Actions();

        parent::__construct('', 'div', $attributes);

        $this->appendToAttribute(Component::ATTRIBUTE_CLASS, 'grid-pagination');
    }

    /**
     * @return int
     */
    public function getTo()
    {
        return min($this->totalCount, $this->from + $this->pageSize - 1);
    }

    /**
     * @return int
     */
    public function getMinPageNumber()
    {
        $currentPage = (int)floor($this->from / $this->pageSize);
        $minPage     = (int)($currentPage - floor($this->numberCount / 2));
        $result      = (int)max($minPage, 0) + 1;

        return $result;
    }

    /**
     * @return int
     */
    public function getMaxPageNumber()
    {
        $currentPage = (int)floor($this->from / $this->pageSize);
        $maxPage     = (int)($currentPage + floor($this->numberCount / 2) + 1);
        $result      = (int)min($maxPage, floor($this->totalCount / $this->pageSize));

        return $result;
    }

    /**
     * @return array
     */
    public function getPageNumbers()
    {
        $minPageNumber = $this->getMinPageNumber();
        $maxPageNumber = $this->getMaxPageNumber();

        $numbers = [];
        for ($i = 0; ($i < $this->numberCount) && ($minPageNumber + $i <= $maxPageNumber); $i++) {
            $numbers[] = $minPageNumber + $i;
        }

        return $numbers;
    }

    public function createButtons()
    {
        $numbers = $this->getPageNumbers();

        $lastIndex = count($numbers) - 1;

        if ($this->from < 1) {
            $this->buttons[] = new Button('<<');
            $this->buttons[] = new Button('<');
        }

        if ($numbers[0] > 1) {
            $this->buttons[] = new Button('...');
        }

        foreach ($numbers as $number) {
            $this->buttons[] = new Button("$number");
        }

        if ($numbers[$lastIndex] * $this->pageSize < $this->totalCount) {
            $this->buttons[] = new Button('...');
        }

        if ($this->to < $this->totalCount) {
            $this->buttons[] = new Button('>');
            $this->buttons[] = new Button('>>');
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $this->createButtons();

        $this->content = (string)$this->buttons;

        return parent::__toString();
    }
}
