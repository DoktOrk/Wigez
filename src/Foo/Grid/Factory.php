<?php

namespace Foo\Grid;


use Foo\Grid\Cell\Cell;
use Foo\Grid\Collection\Actions;
use Foo\Grid\Collection\Cells;
use Foo\Grid\Collection\Rows;
use Foo\Grid\Row\Row;
use Foo\Grid\Table\Table;
use Foo\I18n\ITranslator;
use Opulence\Orm\IEntity;

class Factory
{
    const CELL_ACTIONS_CONTENT = 'grid:actions';
    const CELL_ACTIONS_GROUP   = 'actions';

    /**
     * @param array            $entities
     * @param array            $getters
     * @param array            $headers
     * @param array            $headerAttributes
     * @param array            $bodyAttributes
     * @param array            $tableAttributes
     * @param array            $gridAttributes
     * @param Actions|null     $cellActions
     * @param Actions|null     $gridActions
     * @param ITranslator|null $translator
     *
     * @return Grid
     */
    public static function createGrid(
        array $entities,
        array $getters,
        array $headers,
        array $headerAttributes = [],
        array $bodyAttributes = [],
        array $tableAttributes = [],
        array $gridAttributes = [],
        Actions $cellActions = null,
        Actions $gridActions = null,
        ITranslator $translator = null
    ) {
        $tableBody   = static::createTableBody($entities, $getters, $bodyAttributes, $cellActions);
        $tableHeader = static::createTableHeader($headers, $headerAttributes, $cellActions);

        $table = new Table($tableBody, $tableHeader, $tableAttributes);

        $grid = new Grid($table, null, $gridActions, $gridAttributes);

        $grid->setIndentation(8);

        if ($translator) {
            $grid->setTranslator($translator);
        }

        return $grid;
    }

    /**
     * @param array        $entities
     * @param array        $getters
     * @param array        $bodyAttributes
     * @param Actions|null $actions
     *
     * @return array|Rows
     */
    private static function createTableBody(
        array $entities,
        array $getters,
        array $bodyAttributes = [],
        Actions $actions = null
    ) {
        $tableBody = new Rows();

        foreach ($entities as $entity) {
            $cells = static::createTableRowCell($getters, $bodyAttributes, $entity);

            $rowActions = $actions ? $actions->duplicate() : null;

            $row = new Row($cells, $rowActions);
            $row->setEntity($entity);

            $tableBody[] = $row;
        }

        return $tableBody;
    }

    /**
     * @param array   $getters
     * @param array   $bodyAttributes
     * @param IEntity $entity
     *
     * @return array|Cells
     */
    private static function createTableRowCell(
        array $getters,
        array $bodyAttributes,
        IEntity $entity
    ) {
        $cells = new Cells();

        foreach ($getters as $group => $getter) {
            $content = is_callable($getter) ? $getter($entity) : (string)$entity->$getter();

            $cells[] = new Cell($content, $group, $bodyAttributes, Cell::BODY);
        }

        return $cells;
    }

    /**
     * @param array        $headers
     * @param array        $headerAttributes
     * @param Actions|null $actions
     *
     * @return array|Cells
     */
    private static function createTableHeader(array $headers, array $headerAttributes = [], Actions $actions = null)
    {
        $cells = new Cells(Cells::HEAD);
        foreach ($headers as $group => $content) {
            $cells[] = new Cell($content, $group, $headerAttributes, Cell::HEAD);
        }

        if ($actions) {
            $cells[] = new Cell(static::CELL_ACTIONS_CONTENT, static::CELL_ACTIONS_GROUP, [], Cell::HEAD);
        }

        return $cells;
    }
}