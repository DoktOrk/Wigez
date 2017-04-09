<?php

namespace Foo\Grid\Collection;

use Foo\Grid\Row\IRow;
use InvalidArgumentException;
use LogicException;

class Rows extends BaseCollection
{
    /** @var IRow[] */
    protected $components = [];

    /**
     * @param string|null $tag
     * @param array       $attributes
     */
    public function __construct(string $tag = null, $attributes = [])
    {
        $tag = $tag ?: 'tbody';

        parent::__construct($tag, $attributes);
    }

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
     * @param IRow     $value
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
