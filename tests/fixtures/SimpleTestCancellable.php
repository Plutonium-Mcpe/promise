<?php

namespace Plutonium\Promise;

class SimpleTestCancellable
{
    public $cancelCalled = false;

    public function cancel()
    {
        $this->cancelCalled = true;
    }
}
