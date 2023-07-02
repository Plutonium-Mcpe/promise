<?php

namespace Plutonium\Promise\Internal;

use Plutonium\Promise\PromiseInterface;
use function Plutonium\Promise\resolve;
use Closure;

/**
 * @internal
 *
 * @template Value
 * @template PromiseError
 * @template-implements PromiseInterface<Value, PromiseError>
 */
final class FulfilledPromise implements PromiseInterface
{
	/**
	 * @phpstan-var Value
	 */
    private $value;

	/**
	 * @phpstan-param Value $value
	 */
    public function __construct($value = null)
    {
        if ($value instanceof PromiseInterface) {
            throw new \InvalidArgumentException('You cannot create Plutonium\Promise\FulfilledPromise with a promise. Use Plutonium\Promise\resolve($promiseOrValue) instead.');
        }

        $this->value = $value;
    }

	/**
	 * @template ClosureReturn
	 *
	 * @phpstan-param null|callable(Value): ClosureReturn $onFulfilled
	 * @phpstan-param null|callable(PromiseError): mixed $onRejected
	 *
	 * @phpstan-return PromiseInterface<Value, PromiseError>
	 */
    public function then(callable $onFulfilled = null, callable $onRejected = null): PromiseInterface
    {
        if (null === $onFulfilled) {
            return $this;
        }

        try {
            return resolve($onFulfilled($this->value));
        } catch (\Throwable $exception) {
            return new RejectedPromise($exception);
        }
    }

	/**
	 * @phpstan-param callable(PromiseError): mixed $onRejected
	 */
    public function catch(callable $onRejected): PromiseInterface
    {
        return $this;
    }

	/**
	 * @phpstan-param callable(): mixed $onFulfilledOrRejected
	 */
    public function finally(callable $onFulfilledOrRejected): PromiseInterface
    {
        return $this->then(function ($value) use ($onFulfilledOrRejected): PromiseInterface {
            return resolve($onFulfilledOrRejected())->then(function () use ($value) {
                return $value;
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
     * @deprecated 3.0.0 Use `finally()` instead
     * @see self::finally()
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
