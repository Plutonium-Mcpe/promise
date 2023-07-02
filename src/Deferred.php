<?php

namespace Plutonium\Promise;

use Throwable;

/**
 * @template ResolveResult
 * @template RejectResult of Throwable
 */
final class Deferred
{
    private $promise;
	/**
	 * @phpstan-var callable(ResolveResult): void
	 */
    private $resolveCallback;
	/**
	 * @phpstan-var callable(RejectResult): void
	 */
    private $rejectCallback;

    public function __construct(callable $canceller = null)
    {
        $this->promise = new Promise(function ($resolve, $reject): void {
            $this->resolveCallback = $resolve;
            $this->rejectCallback  = $reject;
        }, $canceller);
    }

	/**
	 * @phpstan-return  PromiseInterface<ResolveResult, RejectResult>
	 */
    public function promise(): PromiseInterface
    {
        return $this->promise;
    }

	/**
	 * @phpstan-param ResolveResult $value
	 */
    public function resolve($value): void
    {
        ($this->resolveCallback)($value);
    }

	/**
	 * @phpstan-param RejectResult $reason
	 */
    public function reject(Throwable $reason): void
    {
        ($this->rejectCallback)($reason);
    }
}
