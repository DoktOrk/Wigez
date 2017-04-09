<?php

namespace Foo\Grid\Collection;

use Foo\Grid\Action\IAction;
use InvalidArgumentException;
use LogicException;

class Actions extends BaseCollection
{
    /** @var IAction[] */
    protected $components = [];

    /**
     * @return IAction
     * @throws LogicException
     */
    public function current()
    {
        /** @var IAction $object */
        $object = parent::current();

        $this->verifyReturn($object, IAction::class);

        return $object;
    }

    /**
     * @param int|null $offset
     * @param IAction  $value
     *
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        $this->verifyArgument($value, IAction::class);

        parent::offsetSet($offset, $value);
    }

    /**
     * @param int $offset
     *
     * @return IAction|null
     * @throws LogicException
     */
    public function offsetGet($offset)
    {
        /** @var IAction $object */
        $object = parent::offsetGet($offset);

        $this->verifyReturn($object, IAction::class);

        return $object;
    }

    /**
     * @return Actions
     */
    public function duplicate(): Actions
    {
        $actionsCopy = new Actions();

        foreach ($this->components as $action) {
            $actionCopy    = $action->duplicate();
            $actionsCopy[] = $actionCopy;
        }

        return $actionsCopy;
    }

}
