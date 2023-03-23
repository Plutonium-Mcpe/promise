<?php

namespace Plutonium\Promise\PromiseTest;

trait FullTestTrait
{
    use PromisePendingTestTrait,
        PromiseSettledTestTrait,
        PromiseFulfilledTestTrait,
        PromiseRejectedTestTrait,
        ResolveTestTrait,
        RejectTestTrait,
        CancelTestTrait;
}
