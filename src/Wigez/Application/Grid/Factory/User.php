<?php

namespace Wigez\Application\Grid\Factory;

use Foo\Grid\Action\Button;
use Foo\Grid\Collection\Actions;
use Foo\Grid\Factory;
use Foo\Grid\Grid;
use Opulence\Routing\Router;

class User extends Base
{
    const GROUP_ID       = 'user-id';
    const GROUP_USERNAME = 'user-username';
    const GROUP_EMAIL    = 'user-email';

    const HEADER_ID       = 'application:userId';
    const HEADER_USERNAME = 'application:userUsername';
    const HEADER_EMAIL    = 'application:userEmail';

    const GETTER_ID       = 'getId';
    const GETTER_USERNAME = 'getUsername';
    const GETTER_EMAIL    = 'getEmail';

    /** @var array */
    protected $headerAttributes = [];

    /** @var array */
    protected $bodyAttributes = [];

    /** @var Router */
    protected $router;

    /**
     * @param array $entities
     *
     * @return Grid
     */
    public function createGrid(array $entities): Grid
    {
        $headers = [
            static::GROUP_ID       => static::HEADER_ID,
            static::GROUP_USERNAME => static::HEADER_USERNAME,
            static::GROUP_EMAIL    => static::HEADER_EMAIL,
        ];
        $getters = [
            static::GROUP_ID       => static::GETTER_ID,
            static::GROUP_USERNAME => static::GETTER_USERNAME,
            static::GROUP_EMAIL    => static::GETTER_EMAIL,
        ];

        $cellActions = $this->getCellActions();

        $grid = Factory::createGrid(
            $entities,
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
            static::ATTRIBUTE_HREF  => ROUTE_USERS_EDIT,
        ];

        $deleteAttributes = [
            static::ATTRIBUTE_CLASS => Button::CLASS_DANGER,
            static::ATTRIBUTE_HREF  => ROUTE_USERS_DELETE,
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


