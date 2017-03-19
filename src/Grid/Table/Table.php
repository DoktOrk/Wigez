<?php

namespace Grid\Table;

use Grid\Cell\Cell;
use Grid\Collection\Actions;
use Grid\Collection\Cells;
use Grid\Collection\Rows;
use Grid\Component\Component;

class Table extends Component implements ITable
{
    /**
     *   %1$s - thead - rows
     *   %2$s - tbody - headers
     */
    const TEMPLATE_CONTENT = '%1$s%2$s';

    const TAG_TABLE = 'table';
    const TAG_HEADERS = 'thead';
    const TAG_HEADER_CELL = 'th';
    const TAG_ROWS = 'tbody';

    /** @var Cells */
    protected $headers;

    /** @var Rows */
    protected $rows;

    /**
     * @param Rows $rows
     * @param Cells|null $headers
     */
    public function __construct(Rows $rows, Cells $headers = null)
    {
        $this->rows = $rows;
        $this->headers = $headers;

        parent::__construct('', static::TAG_TABLE);
    }

    /**
     * @return Cells
     */
    public function getHeader(): Cells
    {
        if ($this->headers !== null) {
            return $this->headers;
        }

        $this->createHeader();

        return $this->headers;
    }

    protected function createHeader()
    {
        $this->headers = new Cells(static::TAG_ROWS);

        if (count($this->rows) === 0) {
            return;
        }

        /** @var Cell $cell */
        foreach ($this->rows[0] as $cell) {
            $group = $cell->getGroup();

            $this->headers[] = new Cell($group, $group, [], static::TAG_HEADER_CELL);
        }
    }

    /**
     * @return Rows
     */
    public function getRows(): Rows
    {
        return $this->rows;
    }

    /**
     * @return bool
     */
    public function hasMassActions(): Actions
    {
        return count($this->massActions);
    }

    /**
     * @param int $num
     * @param string $whitespace
     */
    public function setIndentation(int $num, string $whitespace = '    ')
    {
        foreach ($this->headers as $header) {
            $header->setIndentation($num + 1, $whitespace);
        }

        foreach ($this->rows as $row) {
            $row->setIndentation($num + 1, $whitespace);
        }

        $this->indentation = str_repeat($num, $whitespace);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $thead = (string)$this->headers;
        $tbody = (string)$this->rows;

        $this->content = sprintf(
            static::TEMPLATE_CONTENT,
            $thead,
            $tbody
        );

        return parent::__toString();
    }
}
