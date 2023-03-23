<?php

namespace Plutonium\Promise\PromiseTest;

use Plutonium\Promise\PromiseAdapter\PromiseAdapterInterface;
use Plutonium\Promise\PromiseInterface;

trait PromisePendingTestTrait
{
    /**
     * @return PromiseAdapterInterface
     */
    abstract public function getPromiseTestAdapter(callable $canceller = null);

    /** @test */
    public function thenShouldReturnAPromiseForPendingPromise()
    {
        $adapter = $this->getPromiseTestAdapter();

        self::assertInstanceOf(PromiseInterface::class, $adapter->promise()->then());
    }

    /** @test */
    public function thenShouldReturnAllowNullForPendingPromise()
    {
        $adapter = $this->getPromiseTestAdapter();

        self::assertInstanceOf(PromiseInterface::class, $adapter->promise()->then(null, null));
    }

    /** @test */
    public function cancelShouldReturnNullForPendingPromise()
    {
        $adapter = $this->getPromiseTestAdapter();

        self::assertNull($adapter->promise()->cancel());
    }

    /** @test */
    public function catchShouldNotInvokeRejectionHandlerForPendingPromise()
    {
        $adapter = $this->getPromiseTestAdapter();

        $adapter->settle(null);
        $adapter->promise()->catch($this->expectCallableNever());
    }

    /** @test */
    public function finallyShouldReturnAPromiseForPendingPromise()
    {
        $adapter = $this->getPromiseTestAdapter();

        self::assertInstanceOf(PromiseInterface::class, $adapter->promise()->finally(function () {}));
    }

    /**
     * @test
     * @deprecated
     */
    public function otherwiseShouldNotInvokeRejectionHandlerForPendingPromise()
    {
        $adapter = $this->getPromiseTestAdapter();

        $adapter->settle(null);
        $adapter->promise()->otherwise($this->expectCallableNever());
    }

    /**
     * @test
     * @deprecated
     */
    public function alwaysShouldReturnAPromiseForPendingPromise()
    {
        $adapter = $this->getPromiseTestAdapter();

        self::assertInstanceOf(PromiseInterface::class, $adapter->promise()->always(function () {}));
    }
}
