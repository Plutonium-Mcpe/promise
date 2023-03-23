<?php

namespace Plutonium\Promise\Internal;

use Exception;
use LogicException;
use Plutonium\Promise\PromiseAdapter\CallbackPromiseAdapter;
use Plutonium\Promise\PromiseTest\PromiseRejectedTestTrait;
use Plutonium\Promise\PromiseTest\PromiseSettledTestTrait;
use Plutonium\Promise\TestCase;

class RejectedPromiseTest extends TestCase
{
    use PromiseSettledTestTrait,
        PromiseRejectedTestTrait;

    public function getPromiseTestAdapter(callable $canceller = null)
    {
        $promise = null;

        return new CallbackPromiseAdapter([
            'promise' => function () use (&$promise) {
                if (!$promise) {
                    throw new LogicException('RejectedPromise must be rejected before obtaining the promise');
                }

                return $promise;
            },
            'resolve' => function () {
                throw new LogicException('You cannot call resolve() for Plutonium\Promise\RejectedPromise');
            },
            'reject' => function (\Throwable $reason) use (&$promise) {
                if (!$promise) {
                    $promise = new RejectedPromise($reason);
                }
            },
            'settle' => function ($reason = '') use (&$promise) {
                if (!$promise) {
                    if (!$reason instanceof Exception) {
                        $reason = new Exception((string) $reason);
                    }

                    $promise = new RejectedPromise($reason);
                }
            },
        ]);
    }
}
