<?php

namespace Plutonium\Promise\Adapter;

use Plutonium\Promise\PromiseInterface;
use pocketmine\promise\Promise;
use pocketmine\Server;

/**
 * Adapter for Pocketmine's Promise class.
 *
 * It support only wait() method, due to technical limitation other methods are not supported.
 */
class PocketminePromiseAdapter implements PromiseInterface {
	public function __construct(
		private Promise $promise
	) {
	}

	public function then(?callable $onFulfilled = null, ?callable $onRejected = null) : PromiseInterface {
		throw new \Exception("Not supported");
	}

	public function cancel() : void {
		throw new \Exception("Not supported");
	}

	public function catch(callable $onRejected) : PromiseInterface {
		throw new \Exception("Not supported");
	}

	public function finally(callable $onFulfilledOrRejected) : PromiseInterface {
		throw new \Exception("Not supported");
	}

	public function always(callable $onFulfilledOrRejected): PromiseInterface {
		throw new \Exception("Not supported");
	}

	public function otherwise(callable $onRejected): PromiseInterface {
		throw new \Exception("Not supported");
	}

	public function wait() : void {
		while (!$this->promise->isResolved()) {
			Server::getInstance()->getTickSleeper()->sleepUntil(microtime(true) + 0.01);
		}
	}

	public function isResolved(): bool {
		return $this->promise->isResolved();
	}
}