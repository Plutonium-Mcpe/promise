<?php

namespace Plutonium\Promise\PromiseAdapter;

use Plutonium\Promise\PromiseInterface;

interface PromiseAdapterInterface
{
    public function promise(): ?PromiseInterface;
    public function resolve(): ?PromiseInterface;
    public function reject(): ?PromiseInterface;
    public function settle(): ?PromiseInterface;
}
