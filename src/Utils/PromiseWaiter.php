<?php

namespace Plutonium\Promise\Utils;

use Plutonium\Promise\Adapter\PocketminePromiseAdapter;
use Plutonium\Promise\Exception\TimeoutException;
use Plutonium\Promise\PromiseInterface;
use pocketmine\Server;

class PromiseWaiter {
	private bool $resolved = false;
	/** @var mixed|null $result */
	private $result;

	public function __construct(
		private PromiseInterface $promise,
		private int $timeout = 5
	) {
		if (!$this->promise instanceof PocketminePromiseAdapter) {
			$this->promise->then(function ($result) {
				$this->result = $result;
			}, function ($result) {
				$this->result = $result;
			})->finally(function () {
				$this->resolved = true;
			});
		}
	}

	public function wait() : void {
		$startTime = microtime(true);
		while (($this->promise instanceof PocketminePromiseAdapter && !$this->promise->isResolved()) || (!$this->promise instanceof PocketminePromiseAdapter && !$this->resolved)) {
			if (microtime(true) - $startTime > $this->timeout) {
				throw new TimeoutException();
			}
			Server::getInstance()->getTickSleeper()->sleepUntil($startTime + 0.1);
		}
	}

	/** @return mixed|null */
	public function getResult() {
		return $this->result;
	}
}