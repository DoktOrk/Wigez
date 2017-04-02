<?php

namespace Project\Application\Grid\Factory;

use Grid\Action\Button;
use Grid\Collection\Actions;
use Grid\Factory;
use Grid\Grid;
use Opulence\Routing\Router;

class Customer extends Base
{
    const GROUP_ID = 'customer-id';
    const GROUP_NAME = 'customer-name';
    const GROUP_EMAIL = 'customer-email';
    const GROUP_PASSWORD = 'customer-password';

    const HEADER_ID = 'Id';
    const HEADER_NAME = 'Name';
    const HEADER_EMAIL = 'Email';
    const HEADER_PASSWORD = 'Password';

    const GETTER_ID = 'getId';
    const GETTER_NAME = 'getName';
    const GETTER_EMAIL = 'getEmail';
    const GETTER_PASSWORD = 'getPassword';

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
            static::GROUP_ID            => static::GETTER_ID,
            static::GROUP_NAME          => static::GETTER_NAME,
            static::GROUP_EMAIL         => static::GETTER_EMAIL,
            static::GROUP_PASSWORD      => static::GETTER_PASSWORD,
        ];
        $headers = [
            static::GROUP_ID            => static::HEADER_ID,
            static::GROUP_NAME          => static::HEADER_NAME,
            static::GROUP_EMAIL         => static::HEADER_EMAIL,
            static::GROUP_PASSWORD      => static::HEADER_PASSWORD,
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


