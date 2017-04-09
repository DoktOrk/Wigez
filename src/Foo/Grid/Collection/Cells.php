<?php

namespace Foo\Grid\Collection;

use Foo\Grid\Cell\ICell;
use InvalidArgumentException;
use LogicException;

class Cells extends BaseCollection
{
    const HEAD = 'thead';

    /** @var ICell[] */
    protected $components = [];

    /**
     * @return ICell
     * @throws LogicException
     */
    public function current()
    {
        /** @var ICell $object */
        $object = parent::current();

        $this->verifyReturn($object, ICell::class);

        return $object;
    }

    /**
     * @param int|null $offset
     * @param ICell    $value
     *
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        $this->verifyArgument($value, ICell::class);

        parent::offsetSet($offset, $value);
    }

    /**
     * @param int $offset
     *
     * @return ICell|null
     * @throws LogicException
     */
    public function offsetGet($offset)
    {
        /** @var ICell $object */
        $object = parent::offsetGet($offset);

        $this->verifyReturn($object, ICell::class);

        return $object;
    }
}
