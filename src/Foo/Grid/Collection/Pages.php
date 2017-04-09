<?php

namespace Foo\Grid\Collection;

use InvalidArgumentException;
use LogicException;

class Pages extends BaseCollection
{
    /** @var IPage[] */
    protected $components = [];

    /**
     * @return IPage
     * @throws LogicException
     */
    public function current()
    {
        /** @var IPage $object */
        $object = parent::current();

        $object = $this->verifyReturn($object, IPage::class);

        return $object;
    }

    /**
     * @param int|null $offset
     * @param IPage    $value
     *
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        $this->verifyArgument($value, IPage::class);

        parent::offsetSet($offset, $value);
    }

    /**
     * @param int $offset
     *
     * @return IPage|null
     * @throws LogicException
     */
    public function offsetGet($offset)
    {
        /** @var IPage $object */
        $object = parent::offsetGet($offset);

        $this->verifyReturn($object, IPage::class);

        return $object;
    }
}
