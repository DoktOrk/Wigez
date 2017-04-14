<?php

namespace Foo\Session;

use Opulence\Sessions\ISession;

class FlashService
{
    const ERROR   = 'error';
    const SUCCESS = 'success';

    /** @var ISession */
    protected $session;

    /**
     * Helper constructor.
     *
     * @param ISession $session
     */
    public function __construct(ISession $session)
    {
        $this->session = $session;
    }

    /**
     * @param string[] $value
     */
    public function mergeSuccessMessages(array $value)
    {
        $currentValue = (array)$this->session->get(static::SUCCESS);

        $newValue = array_merge($currentValue, $value);

        $this->session->flash(static::SUCCESS, $newValue);
    }

    /**
     * @param string[] $value
     */
    public function mergeErrorMessages(array $value)
    {
        $currentValue = (array)$this->session->get(static::ERROR);

        $newValue = array_merge($currentValue, $value);

        $this->session->flash(static::ERROR, $newValue);
    }

    /**
     * @param string   $key
     * @param string[] $value
     */
    public function mergeFlashMessages(string $key, array $value)
    {
        $currentValue = (array)$this->session->get($key);

        $newValue = array_merge($currentValue, $value);

        $this->session->flash($key, $newValue);
    }

    /**
     * @return array
     */
    public function retrieveSuccessMessages()
    {
        return (array)$this->session->get(static::SUCCESS);
    }

    /**
     * @return array
     */
    public function retrieveErrorMessages()
    {
        return (array)$this->session->get(static::ERROR);
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function retrieveFlashMessages(string $key)
    {
        return (array)$this->session->get($key);
    }
}

