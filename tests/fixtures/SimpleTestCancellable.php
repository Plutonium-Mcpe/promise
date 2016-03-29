<?php

namespace React\Promise;

class SimpleTestCancellable
{
    public $cancelCalled = false;

    public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onProgress = null)
    {
        return new self();
    }

    public function cancel()
    {
        $this->cancelCalled = true;
    }
}
