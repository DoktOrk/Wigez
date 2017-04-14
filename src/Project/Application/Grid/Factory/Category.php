<?php

namespace Project\Application\Grid\Factory;

use Foo\Grid\Action\Button;
use Foo\Grid\Collection\Actions;
use Foo\Grid\Factory;
use Foo\Grid\Grid;
use Opulence\Routing\Router;

class Category extends Base
{
    const GROUP_ID   = 'category-id';
    const GROUP_NAME = 'category-name';

    const HEADER_ID   = 'application:categoryId';
    const HEADER_NAME = 'application:categoryName';

    const GETTER_ID   = 'getId';
    const GETTER_NAME = 'getName';

    /** @var array */
    protected $headerAttributes = [];

    /** @var array */
    protected $bodyAttributes = [];

    /** @var Router */
    protected $router;

    /**
     * @param array $CATEGORIES
     *
     * @return Grid
     */
    public function createGrid(array $CATEGORIES): Grid
    {
        $getters = [
            static::GROUP_ID   => static::GETTER_ID,
            static::GROUP_NAME => static::GETTER_NAME,
        ];
        $headers = [
            static::GROUP_ID   => static::HEADER_ID,
            static::GROUP_NAME => static::HEADER_NAME,
        ];

        $cellActions = $this->getCellActions();

        $grid = Factory::createGrid(
            $CATEGORIES,
            $getters,
            $headers,
            $this->headerAttributes,
            $this->bodyAttributes,
            $this->tableAttributes,
            $this->gridAttributes,
            $cellActions,
            null,
            $this->translator
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
            static::ATTRIBUTE_CLASS => Button::CLASS_PRIMARY,
            static::ATTRIBUTE_HREF  => ROUTE_CATEGORIES_EDIT,
        ];

        $deleteAttributes = [
            static::ATTRIBUTE_CLASS => Button::CLASS_DANGER,
            static::ATTRIBUTE_HREF  => ROUTE_CATEGORIES_DELETE,
        ];

        $cellActions   = new Actions();
        $cellActions[] = new Button(
            static::LABEL_EDIT,
            Button::TAG_A,
            $editAttributes,
            $attributeCallbacks,
            $this->translator
        );
        $cellActions[] = new Button(
            static::LABEL_DELETE,
            Button::TAG_A,
            $deleteAttributes,
            $attributeCallbacks,
            $this->translator
        );

        return $cellActions;
    }
}


