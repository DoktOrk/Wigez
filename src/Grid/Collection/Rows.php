<?php

namespace Grid\Collection;

use InvalidArgumentException;
use LogicException;
use Grid\Row\IRow;

class Rows extends BaseCollection
{
    /** @var IRow[] */
    protected $components = [];

    /**
     * @return IRow
     * @throws LogicException
     */
    public function current()
    {
        /** @var IRow $object */
        $object = parent::current();

        $this->verifyReturn($object, IRow::class);

        return $object;
    }

    /**
     * @param int|null $offset
     * @param IRow $value
     *
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        $this->verifyArgument($value, IRow::class);

        parent::offsetSet($offset, $value);
    }

    /**
     * @param int $offset
     *
     * @return IRow|null
     * @throws LogicException
     */
    public function offsetGet($offset)
    {
        /** @var IRow $object */
        $object = parent::offsetGet($offset);

        $this->verifyReturn($object, IRow::class);

        return $object;
    }
}
