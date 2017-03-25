<?php

namespace Project\Application\Grid\Factory;

use Grid\Action\Button;
use Grid\Collection\Actions;
use Grid\Factory;

class Page
{
    const HEADER_ID = 'Id';
    const HEADER_TITLE = 'Title';

    const GROUP_ID = 'page-id';
    const GROUP_TITLE = 'page-title';

    const GETTER_ID = 'getId';
    const GETTER_TITLE = 'getTitle';

    const ATTRIBUTE_CLASS = 'class';
    const ATTRIBUTE_HREF = 'href';

    /** @var array */
    protected $headerAttributes = [];

    /** @var array */
    protected $bodyAttributes = [];

    /** @var array */
    protected $tableAttributes = [
        self::ATTRIBUTE_CLASS => 'table table-striped table-hover table-bordered'
    ];

    /** @var array */
    protected $gridAttributes = [
        self::ATTRIBUTE_CLASS => 'grid'
    ];

    /**
     * @param array $pages
     *
     * @return \Grid\Grid
     */
    public function createGrid(array $pages)
    {
        $getters = [static::GROUP_ID => static::GETTER_ID, static::GROUP_TITLE => static::GETTER_TITLE];
        $headers = [static::GROUP_ID => static::HEADER_ID, static::GROUP_TITLE => static::HEADER_TITLE];

        $cellActions = $this->getCellActions();

        $grid = Factory::createGrid(
            $pages,
            $getters,
            $headers,
            $this->headerAttributes,
            $this->bodyAttributes,
            $this->tableAttributes,
            $this->gridAttributes,
            $cellActions
        );

        return $grid;
    }

    /**
     * @return Actions
     */
    protected function getCellActions() :Actions
    {
        $attributeCallbacks = [
            self::ATTRIBUTE_HREF => Button::getDefaultCallback(),
        ];

        $editAttributes = [
            self::ATTRIBUTE_HREF => 'page/%d/edit'
        ];

        $deleteAttributes = [
            self::ATTRIBUTE_HREF => 'page/%d/delete'
        ];

        $cellActions = new Actions();
        $cellActions[] = new Button('Edit', Button::TAG_A, $editAttributes, $attributeCallbacks);
        $cellActions[] = new Button('Delete', Button::TAG_A, $deleteAttributes, $attributeCallbacks);

        return $cellActions;
    }
}


