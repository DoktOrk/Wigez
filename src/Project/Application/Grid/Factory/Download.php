<?php

namespace Project\Application\Grid\Factory;

use Grid\Action\Button;
use Grid\Collection\Actions;
use Grid\Factory;
use Grid\Grid;
use Opulence\Routing\Router;
use Project\Domain\Entities\Download as Entity;

class Download extends Base
{
    const GROUP_ID = 'download-id';
    const GROUP_FILE = 'download-file';
    const GROUP_CUSTOMER = 'download-customer';
    const GROUP_DOWNLOADED_AT = 'download-downloaded-at';

    const HEADER_ID = 'Id';
    const HEADER_FILE = 'File';
    const HEADER_CUSTOMER = 'Customer';
    const HEADER_DOWNLOADED_AT = 'Downloaded At';

    const GETTER_ID = 'getId';
    const GETTER_FILE = 'getFile';
    const GETTER_CUSTOMER = 'getCustomer';
    const GETTER_DOWNLOADED_AT = 'getDownloadedAt';

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
        $headers = [
            static::GROUP_ID            => static::HEADER_ID,
            static::GROUP_FILE          => static::HEADER_FILE,
            static::GROUP_CUSTOMER      => static::HEADER_CUSTOMER,
            static::GROUP_DOWNLOADED_AT => static::HEADER_DOWNLOADED_AT,
        ];
        $getters = [
            static::GROUP_ID            => static::GETTER_ID,
            static::GROUP_FILE          => static::GETTER_FILE,
            static::GROUP_CUSTOMER      => static::GETTER_CUSTOMER,
            static::GROUP_DOWNLOADED_AT => [$this, 'getDownloadedAt'],
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

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public function getDownloadedAt(Entity $entity): string
    {
        return $entity->getDownloadedAt()->format(Entity::DATE_FORMAT);
    }
}


