<?php

namespace Grid\Action;

use Grid\Component\ComponentTest;

class ButtonTest extends ComponentTest
{
    const DEFAULT_TEMPLATE = '<button foo="foo baz" bar="bar baz">Test</button>';

    const TAG = 'button';

    /** @var Button */
    protected $sut;

    public function setUp()
    {
        $this->sut = new Button(static::LABEL, static::TAG, $this->getDefaultAttributes());
    }
}

