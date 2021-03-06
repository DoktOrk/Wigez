<?php

namespace Wigez\Application\Grid\Factory;

use Foo\Grid\Action\Button;
use Foo\Grid\Collection\Actions;
use Foo\Grid\Factory;
use Foo\Grid\Grid;
use Opulence\Routing\Router;
use Wigez\Domain\Entities\File as Entity;

class File extends Base
{
    const GROUP_ID          = 'file-id';
    const GROUP_FILENAME    = 'file-filename';
    const GROUP_CATEGORY    = 'file-category';
    const GROUP_DESCRIPTION = 'file-description';
    const GROUP_UPLOADED_AT = 'file-uploaded-at';

    const HEADER_ID          = 'application:fileId';
    const HEADER_FILENAME    = 'application:fileFilename';
    const HEADER_CATEGORY    = 'application:fileCategory';
    const HEADER_DESCRIPTION = 'application:fileDescription';
    const HEADER_UPLOADED_AT = 'application:fileUploadedAt';

    const GETTER_ID          = 'getId';
    const GETTER_FILENAME    = 'getFilename';
    const GETTER_CATEGORY    = 'getCategory';
    const GETTER_DESCRIPTION = 'getDescription';

    const LABEL_DOWNLOAD = 'grid:download';

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
            static::GROUP_FILENAME    => static::HEADER_FILENAME,
            static::GROUP_CATEGORY    => static::HEADER_CATEGORY,
            static::GROUP_DESCRIPTION => static::HEADER_DESCRIPTION,
            static::GROUP_UPLOADED_AT => static::HEADER_UPLOADED_AT,
        ];
        $getters = [
            static::GROUP_ID          => static::GETTER_ID,
            static::GROUP_FILENAME    => static::GETTER_FILENAME,
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
            static::ATTRIBUTE_CLASS => Button::CLASS_PRIMARY,
            static::ATTRIBUTE_HREF  => ROUTE_FILES_EDIT,
        ];

        $deleteAttributes = [
            static::ATTRIBUTE_CLASS => Button::CLASS_DANGER,
            static::ATTRIBUTE_HREF  => ROUTE_FILES_DELETE,
        ];

        $downloadAttributes = [
            static::ATTRIBUTE_CLASS => Button::CLASS_SUCCESS,
            static::ATTRIBUTE_HREF  => ROUTE_FILES_DOWNLOAD,
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
        $cellActions[] = new Button(
            static::LABEL_DOWNLOAD,
            Button::TAG_A,
            $downloadAttributes,
            $attributeCallbacks,
            $this->translator
        );

        return $cellActions;
    }
}


