<?php

namespace Plutonium\Promise\PromiseAdapter;

use Plutonium\Promise\PromiseInterface;

class CallbackPromiseAdapter implements PromiseAdapterInterface
{
    private $callbacks;

    public function __construct(array $callbacks)
    {
        $this->callbacks = $callbacks;
    }

    public function promise(): ?PromiseInterface
    {
        return ($this->callbacks['promise'])(...func_get_args());
    }

    public function resolve(): ?PromiseInterface
    {
        return ($this->callbacks['resolve'])(...func_get_args());
    }

    public function reject(): ?PromiseInterface
    {
        return ($this->callbacks['reject'])(...func_get_args());
    }

    public function settle(): ?PromiseInterface
    {
        return ($this->callbacks['settle'])(...func_get_args());
    }
}
