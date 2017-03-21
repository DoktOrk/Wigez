<?php

namespace Grid\Collection;

use Grid\Action\IAction;
use Grid\Component\Component;
use InvalidArgumentException;
use LogicException;

class Actions extends BaseCollection
{
    /** @var Component[] */
    protected $components = [];

    /**
     * @return Component
     * @throws LogicException
     */
    public function current()
    {
        /** @var Component $object */
        $object = parent::current();

        $object = $this->verifyReturn($object, Component::class);

        return $object;
    }

    /**
     * @param int|null $offset
     * @param Component $value
     *
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        $this->verifyArgument($value, Component::class);

        parent::offsetSet($offset, $value);
    }

    /**
     * @param int $offset
     *
     * @return Component|null
     * @throws LogicException
     */
    public function offsetGet($offset)
    {
        /** @var IAction $object */
        $object = parent::offsetGet($offset);

        $object = $this->verifyReturn($object, Component::class);

        return $object;
    }
}
