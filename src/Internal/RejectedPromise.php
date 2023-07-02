<?php

namespace Plutonium\Promise\Internal;

use Closure;
use Plutonium\Promise\PromiseInterface;
use Throwable;
use function Plutonium\Promise\_checkTypehint;
use function Plutonium\Promise\resolve;

/**
 * @internal
 *
 * @template Value of Throwable
 */
final class RejectedPromise implements PromiseInterface
{
	/**
	 * @var Value $reason
	 */
    private $reason;

	/**
	 * @phpstan-param Value $reason
	 */
    public function __construct(Throwable $reason)
    {
        $this->reason = $reason;
    }

	/**
	 * @phpstan-param null|Closure(): mixed $onFulfilled
	 * @phpstan-param null|Closure(Value): mixed $onRejected
	 */
    public function then(Closure $onFulfilled = null, Closure $onRejected = null): PromiseInterface
    {
        if (null === $onRejected) {
            return $this;
        }

        try {
            return resolve($onRejected($this->reason));
        } catch (Throwable $exception) {
            return new RejectedPromise($exception);
        }
    }

	/**
	 * @phpstan-param Closure(Value): mixed $onRejected
	 */
    public function catch(Closure $onRejected): PromiseInterface
    {
        if (!_checkTypehint($onRejected, $this->reason)) {
            return $this;
        }

        return $this->then(null, $onRejected);
    }

	/**
	 * @phpstan-param Closure(): mixed $onFulfilledOrRejected
	 */
    public function finally(Closure $onFulfilledOrRejected): PromiseInterface
    {
        return $this->then(null, function (Throwable $reason) use ($onFulfilledOrRejected): PromiseInterface {
            return resolve($onFulfilledOrRejected())->then(function () use ($reason): PromiseInterface {
                return new RejectedPromise($reason);
            });
        });
    }

    public function cancel(): void
    {
    }

    /**
     * @deprecated 3.0.0 Use `catch()` instead
     * @see self::catch()
     */
    public function otherwise(callable $onRejected): PromiseInterface
    {
        return $this->catch($onRejected);
    }

    /**
     * @deprecated 3.0.0 Use `always()` instead
     * @see self::always()
     */
    public function always(callable $onFulfilledOrRejected): PromiseInterface
    {
        return $this->finally($onFulfilledOrRejected);
    }

	public function wait(): void {
		// NOOP
	}

	public function isResolved(): bool {
		return true;
	}
}
