<?php

namespace Foo\Grid\Collection;

use Foo\Grid\Filter\IFilter;
use InvalidArgumentException;
use LogicException;

class Filters extends BaseCollection
{
    /** @var IFilter[] */
    protected $components = [];

    /**
     * @return IFilter
     * @throws LogicException
     */
    public function current()
    {
        /** @var IFilter $object */
        $object = parent::current();

        $object = $this->verifyReturn($object, IFilter::class);

        return $object;
    }

    /**
     * @param int|null $offset
     * @param IFilter  $value
     *
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        $this->verifyArgument($value, IFilter::class);

        parent::offsetSet($offset, $value);
    }

    /**
     * @param int $offset
     *
     * @return IFilter|null
     * @throws LogicException
     */
    public function offsetGet($offset)
    {
        /** @var IFilter $object */
        $object = parent::offsetGet($offset);

        $this->verifyReturn($object, IFilter::class);

        return $object;
    }
}
