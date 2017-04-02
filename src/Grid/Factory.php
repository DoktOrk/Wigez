<?php

namespace Grid;


use Grid\Cell\Cell;
use Grid\Collection\Actions;
use Grid\Collection\Cells;
use Grid\Collection\Rows;
use Grid\Row\Row;
use Grid\Table\Table;

class Factory
{
    const CELL_ACTIONS_CONTENT = 'Actions';
    const CELL_ACTIONS_GROUP = 'actions';

    /**
     * @param array        $entities
     * @param array        $getters
     * @param array        $headers
     * @param array        $headerAttributes
     * @param array        $bodyAttributes
     * @param array        $tableAttributes
     * @param array        $gridAttributes
     * @param Actions|null $gridActions
     * @param Actions|null $cellActions
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
        Actions $gridActions = null
    ) {
        $tableBody   = static::createTableBody($entities, $getters, $bodyAttributes, $cellActions);
        $tableHeader = static::createTableHeader($headers, $headerAttributes, $cellActions);

        $table = new Table($tableBody, $tableHeader, $tableAttributes);

        $grid = new Grid($table, null, $gridActions, $gridAttributes);

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
            $cells = new Cells();

            foreach ($getters as $group => $getter) {
                $content = is_callable($getter) ? $getter($entity) : (string)$entity->$getter();

                $cells[] = new Cell($content, $group, $bodyAttributes, Cell::BODY);
            }

            $row = new Row($cells, $actions->duplicate());
            $row->setEntity($entity);

            $tableBody[] = $row;
        }

        return $tableBody;
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
            $cells[] = new Cell(static::CELL_ACTIONS_CONTENT, static::CELL_ACTIONS_GROUP);
        }

        return $cells;
    }
}