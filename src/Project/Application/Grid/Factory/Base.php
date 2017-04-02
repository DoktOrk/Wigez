<?php

namespace Project\Application\Grid\Factory;

use Opulence\Orm\IEntity;
use Opulence\Routing\Urls\UrlGenerator;

abstract class Base implements IFactory
{
    const ATTRIBUTE_CLASS = 'class';
    const ATTRIBUTE_HREF = 'href';

    const CLASS_PRIMARY = 'btn btn-primary';
    const CLASS_DANGER = 'btn btn-danger';

    const LABEL_EDIT = 'Edit';
    const LABEL_DELETE = 'Delete';

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var array */
    protected $tableAttributes = [
        self::ATTRIBUTE_CLASS => 'table table-striped table-hover table-bordered',
    ];

    /** @var array */
    protected $gridAttributes = [
        self::ATTRIBUTE_CLASS => 'grid',
    ];

    /**
     * Page constructor.
     *
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return \Closure
     */
    protected function getAttributeCallbacks(): array
    {
        $urlGenerator = $this->urlGenerator;

        $closure = function ($attribute, IEntity $entity) use ($urlGenerator) {
            return $urlGenerator->createFromName($attribute, $entity->getId());
        };

        $attributeCallbacks = [
            self::ATTRIBUTE_HREF => $closure,
        ];

        return $attributeCallbacks;
    }
}