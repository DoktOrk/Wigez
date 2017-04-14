<?php

namespace Project\Application\Grid\Factory;

use Foo\I18n\ITranslator;
use Opulence\Orm\IEntity;
use Opulence\Routing\Urls\UrlGenerator;

abstract class Base implements IFactory
{
    const ATTRIBUTE_CLASS = 'class';
    const ATTRIBUTE_HREF  = 'href';

    const LABEL_EDIT   = 'grid:editItem';
    const LABEL_DELETE = 'grid:deleteItem';

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var ITranslator */
    protected $translator;

    /** @var array */
    protected $tableAttributes = [
        self::ATTRIBUTE_CLASS => 'table table-striped table-hover table-bordered',
    ];

    /** @var array */
    protected $gridAttributes = [
        self::ATTRIBUTE_CLASS => 'grid',
    ];

    /**
     * Base constructor.
     *
     * @param UrlGenerator $urlGenerator
     * @param ITranslator  $translator
     */
    public function __construct(UrlGenerator $urlGenerator, ITranslator $translator)
    {
        $this->urlGenerator = $urlGenerator;

        $this->translator = $translator;
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