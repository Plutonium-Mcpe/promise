<?php

namespace Plutonium\Promise\Internal;

use InvalidArgumentException;
use LogicException;
use Plutonium\Promise\PromiseAdapter\CallbackPromiseAdapter;
use Plutonium\Promise\PromiseTest\PromiseFulfilledTestTrait;
use Plutonium\Promise\PromiseTest\PromiseSettledTestTrait;
use Plutonium\Promise\TestCase;

class FulfilledPromiseTest extends TestCase
{
    use PromiseSettledTestTrait,
        PromiseFulfilledTestTrait;

    public function getPromiseTestAdapter(callable $canceller = null)
    {
        $promise = null;

        return new CallbackPromiseAdapter([
            'promise' => function () use (&$promise) {
                if (!$promise) {
                    throw new LogicException('FulfilledPromise must be resolved before obtaining the promise');
                }

                return $promise;
            },
            'resolve' => function ($value = null) use (&$promise) {
                if (!$promise) {
                    $promise = new FulfilledPromise($value);
                }
            },
            'reject' => function () {
                throw new LogicException('You cannot call reject() for Plutonium\Promise\FulfilledPromise');
            },
            'settle' => function ($value = null) use (&$promise) {
                if (!$promise) {
                    $promise = new FulfilledPromise($value);
                }
            },
        ]);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionIfConstructedWithAPromise()
    {
        $this->expectException(InvalidArgumentException::class);
        return new FulfilledPromise(new FulfilledPromise());
    }
}
