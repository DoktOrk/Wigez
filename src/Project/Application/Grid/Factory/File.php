<?php

namespace Project\Application\Grid\Factory;

use Foo\Grid\Action\Button;
use Foo\Grid\Collection\Actions;
use Foo\Grid\Factory;
use Foo\Grid\Grid;
use Opulence\Routing\Router;
use Project\Domain\Entities\File as Entity;

class File extends Base
{
    const GROUP_ID          = 'file-id';
    const GROUP_FILE        = 'file-file';
    const GROUP_CATEGORY    = 'file-category';
    const GROUP_DESCRIPTION = 'file-description';
    const GROUP_UPLOADED_AT = 'file-uploaded-at';

    const HEADER_ID          = 'application:fileId';
    const HEADER_FILE        = 'application:fileFile';
    const HEADER_CATEGORY    = 'application:fileCategory';
    const HEADER_DESCRIPTION = 'application:fileDescription';
    const HEADER_UPLOADED_AT = 'application:fileUploadedAt';

    const GETTER_ID          = 'getId';
    const GETTER_FILE        = 'getFile';
    const GETTER_CATEGORY    = 'getCategory';
    const GETTER_DESCRIPTION = 'getDescription';

    /** @var array */
    protected $headerAttributes = [];

    /** @var array */
    protected $bodyAttributes = [];

    /** @var Router */
    protected $router;

    /**
     * @param array $FILES
     *
     * @return Grid
     */
    public function createGrid(array $FILES): Grid
    {
        $headers = [
            static::GROUP_ID          => static::HEADER_ID,
            static::GROUP_FILE        => static::HEADER_FILE,
            static::GROUP_CATEGORY    => static::HEADER_CATEGORY,
            static::GROUP_DESCRIPTION => static::HEADER_DESCRIPTION,
            static::GROUP_UPLOADED_AT => static::HEADER_UPLOADED_AT,
        ];
        $getters = [
            static::GROUP_ID          => static::GETTER_ID,
            static::GROUP_FILE        => static::GETTER_FILE,
            static::GROUP_CATEGORY    => static::GETTER_CATEGORY,
            static::GROUP_DESCRIPTION => static::GETTER_DESCRIPTION,
            static::GROUP_UPLOADED_AT => [$this, 'getUploadedAt'],
        ];

        $cellActions = $this->getCellActions();

        $grid = Factory::createGrid(
            $FILES,
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
    public function getUploadedAt(Entity $entity): string
    {
        return $entity->getUploadedAt()->format(Entity::DATE_FORMAT);
    }

    /**
     * @return Actions
     */
    protected function getCellActions(): Actions
    {
        $attributeCallbacks = $this->getAttributeCallbacks();

        $editAttributes = [
            static::ATTRIBUTE_CLASS => static::CLASS_PRIMARY,
            static::ATTRIBUTE_HREF  => ROUTE_FILES_EDIT,
        ];

        $deleteAttributes = [
            static::ATTRIBUTE_CLASS => static::CLASS_DANGER,
            static::ATTRIBUTE_HREF  => ROUTE_FILES_DELETE,
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


