<?php

namespace Foo\Grid\Table;

use Foo\Grid\Collection\Cells;
use Foo\Grid\Collection\Rows;
use Foo\Grid\Component\Component;
use Foo\I18n\ITranslator;

class Table extends Component implements ITable
{
    /**
     *   %1$s - thead - rows
     *   %2$s - tbody - headers
     */
    const TEMPLATE_CONTENT = '%1$s%2$s';

    const TAG_TABLE       = 'table';
    const TAG_HEADERS     = 'thead';
    const TAG_HEADER_CELL = 'th';
    const TAG_ROWS        = 'tbody';

    /** @var Cells */
    protected $headers;

    /** @var Rows */
    protected $rows;

    /**
     * @param Rows  $rows
     * @param Cells $headers
     * @param array $attributes
     */
    public function __construct(Rows $rows, Cells $headers, array $attributes = [])
    {
        $this->rows    = $rows;
        $this->headers = $headers;

        parent::__construct('', static::TAG_TABLE, $attributes);
    }

    /**
     * @return Cells
     */
    public function getHeader(): Cells
    {
        return $this->headers;
    }

    /**
     * @return Rows
     */
    public function getRows(): Rows
    {
        return $this->rows;
    }

    /**
     * @param int    $num
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

        $this->indentation = str_repeat($whitespace, $num);
    }

    /**
     * @param ITranslator $translator
     */
    public function setTranslator(ITranslator $translator)
    {
        foreach ($this->headers as $header) {
            $header->setTranslator($translator);
        }
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
