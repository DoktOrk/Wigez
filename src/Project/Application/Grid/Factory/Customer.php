<?php

namespace Project\Application\Grid\Factory;

use Foo\Grid\Action\Button;
use Foo\Grid\Collection\Actions;
use Foo\Grid\Factory;
use Foo\Grid\Grid;
use Opulence\Routing\Router;
use Project\Domain\Entities\Customer as Entity;

class Customer extends Base
{
    const GROUP_ID         = 'customer-id';
    const GROUP_NAME       = 'customer-name';
    const GROUP_EMAIL      = 'customer-email';
    const GROUP_CATEGORIES = 'customer-categories';

    const HEADER_ID         = 'application:customerId';
    const HEADER_NAME       = 'application:customerName';
    const HEADER_EMAIL      = 'application:customerEmail';
    const HEADER_CATEGORIES = 'application:customerCategories';

    const GETTER_ID    = 'getId';
    const GETTER_NAME  = 'getName';
    const GETTER_EMAIL = 'getEmail';

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
            static::GROUP_ID         => static::HEADER_ID,
            static::GROUP_NAME       => static::HEADER_NAME,
            static::GROUP_EMAIL      => static::HEADER_EMAIL,
            static::GROUP_CATEGORIES => static::HEADER_CATEGORIES,
        ];
        $getters = [
            static::GROUP_ID         => static::GETTER_ID,
            static::GROUP_NAME       => static::GETTER_NAME,
            static::GROUP_EMAIL      => static::GETTER_EMAIL,
            static::GROUP_CATEGORIES => [$this, 'getCategoryNames'],
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
     * @param Entity $entity
     *
     * @return string
     */
    public function getCategoryNames(Entity $entity): string
    {
        $categoryNames = [];

        foreach ($entity->getCategories() as $category) {
            $categoryNames[] = $category->getName();
        }

        return implode(', ', $categoryNames);
    }

    /**
     * @return Actions
     */
    protected function getCellActions(): Actions
    {
        $attributeCallbacks = $this->getAttributeCallbacks();

        $editAttributes = [
            static::ATTRIBUTE_CLASS => Button::CLASS_PRIMARY,
            static::ATTRIBUTE_HREF  => ROUTE_CUSTOMERS_EDIT,
        ];

        $deleteAttributes = [
            static::ATTRIBUTE_CLASS => Button::CLASS_DANGER,
            static::ATTRIBUTE_HREF  => ROUTE_CUSTOMERS_DELETE,
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


