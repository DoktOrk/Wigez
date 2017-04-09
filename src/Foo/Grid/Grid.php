<?php

namespace Foo\Grid;

use Foo\Grid\Collection\Actions;
use Foo\Grid\Collection\Filters;
use Foo\Grid\Component\Component;
use Foo\Grid\Helper\StringHelper;
use Foo\Grid\Table\ITable;
use Foo\I18n\ITranslator;

class Grid extends Component implements IGrid
{
    /**
     *   %1$s - filter
     *   %2$s - actions
     *   %3$s - table
     */
    const TEMPLATE_CONTENT = '%1$s%2$s%3$s';

    const TAG_GRID    = 'div';
    const TAG_FILTER  = 'div';
    const TAG_ACTIONS = 'div';
    const TAG_FORM    = 'form';

    const ATTRIBUTE_GRID_CLASS    = 'grid';
    const ATTRIBUTE_FILTER_CLASS  = 'grid-filters';
    const ATTRIBUTE_ACTIONS_CLASS = 'grid-actions';

    const ATTRIBUTES_FORM = ['class' => 'grid-form'];

    /** @var string */
    protected $containerClass = '';

    /** @var ITable */
    protected $table;

    /** @var Filters */
    protected $filters;

    /** @var Actions */
    protected $actions;

    /** @var string */
    protected $whitespace;

    /**
     * @param ITable       $rows
     * @param Filters|null $filters
     * @param Actions|null $massActions
     * @param array        $attributes
     */
    public function __construct(
        ITable $table,
        Filters $filters = null,
        Actions $actions = null,
        array $attributes = []
    ) {
        $this->table = $table;

        parent::__construct('', static::TAG_GRID, $attributes);

        $this->appendToAttribute(Component::ATTRIBUTE_CLASS, static::ATTRIBUTE_GRID_CLASS);

        if ($actions) {
            $this->actions = $actions;
            $this->actions->appendToAttribute(Component::ATTRIBUTE_CLASS, static::ATTRIBUTE_ACTIONS_CLASS);
        }

        if ($filters) {
            $this->filters = $filters;
            $this->filters->appendToAttribute(Component::ATTRIBUTE_CLASS, static::ATTRIBUTE_FILTER_CLASS);
        }
    }

    /**
     * @param int    $num
     * @param string $whitespace
     */
    public function setIndentation(int $num, string $whitespace = '    ')
    {
        $indentationStep = $this->filters || $this->actions ? 2 : 1;

        $this->table->setIndentation($num + $indentationStep, $whitespace);

        if ($this->filters) {
            $this->filters->setIndentation($num + $indentationStep, $whitespace);
        }

        $this->indentation = str_repeat($whitespace, $num);
        $this->whitespace  = $whitespace;
    }

    /**
     * @param ITranslator $translator
     */
    public function setTranslator(ITranslator $translator)
    {
        $this->table->setTranslator($translator);

        if ($this->filters) {
            $this->filters->setTranslator($translator);
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $filters = (string)$this->filters;
        $actions = $this->actions ? (string)$this->actions : '';
        $table   = (string)$this->table;

        $this->content = sprintf(static::TEMPLATE_CONTENT, $filters, $actions, $table);

        $this->wrapContentInForm();

        return parent::__toString();
    }

    /**
     * @return ITable
     */
    public function getTable(): ITable
    {
        return $this->table;
    }

    /**
     * @param Filters $filters
     */
    public function setFilter(Filters $filters)
    {
        $this->filters = $filters;
    }

    protected function wrapContentInForm()
    {
        if (!$this->actions && !$this->filters) {
            return;
        }

        $whitespace = $this->indentation . $this->whitespace;

        $this->content = StringHelper::wrapInTag(
            $this->content,
            static::TAG_FORM,
            static::ATTRIBUTES_FORM,
            $whitespace
        );
    }
}
