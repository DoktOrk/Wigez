<?php

namespace Project\Application\Grid\Factory;

use Grid\Action\Button;
use Grid\Collection\Actions;
use Grid\Factory;
use Grid\Grid;
use Opulence\Routing\Router;

class Category extends Base
{
    const GROUP_ID = 'category-id';
    const GROUP_NAME = 'category-name';
    const GROUP_POS = 'category-pos';

    const HEADER_ID = 'Id';
    const HEADER_NAME = 'Name';
    const HEADER_POS = 'Pos';

    const GETTER_ID = 'getId';
    const GETTER_NAME = 'getName';
    const GETTER_POS = 'getPos';

    /** @var array */
    protected $headerAttributes = [];

    /** @var array */
    protected $bodyAttributes = [];

    /** @var Router */
    protected $router;

    /**
     * @param array $pages
     *
     * @return Grid
     */
    public function createGrid(array $pages): Grid
    {
        $getters = [
            static::GROUP_ID   => static::GETTER_ID,
            static::GROUP_NAME => static::GETTER_NAME,
            static::GROUP_POS  => static::GETTER_POS,
        ];
        $headers = [
            static::GROUP_ID   => static::HEADER_ID,
            static::GROUP_NAME => static::HEADER_NAME,
            static::GROUP_POS  => static::HEADER_POS,
        ];

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
    protected function getCellActions(): Actions
    {
        $attributeCallbacks = $this->getAttributeCallbacks();

        $editAttributes = [
            static::ATTRIBUTE_CLASS => static::CLASS_PRIMARY,
            static::ATTRIBUTE_HREF  => ROUTE_PAGES_EDIT,
        ];

        $deleteAttributes = [
            static::ATTRIBUTE_CLASS => static::CLASS_DANGER,
            static::ATTRIBUTE_HREF  => ROUTE_PAGES_DELETE,
        ];

        $cellActions   = new Actions();
        $cellActions[] = new Button(
            static::LABEL_EDIT,
            Button::TAG_A,
            $editAttributes,
            $attributeCallbacks
        );
        $cellActions[] = new Button(
            static::LABEL_DELETE,
            Button::TAG_A,
            $deleteAttributes,
            $attributeCallbacks
        );

        return $cellActions;
    }
}


