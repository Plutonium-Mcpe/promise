<?php

namespace Plutonium\Promise\Utils;

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
		$this->promise->then(function ($result) {
			$this->resolved = true;
			$this->result = $result;
		}, function ($result) {
			$this->resolved = true;
			$this->result = $result;
		});
	}

	public function wait() : void {
		$startTime = microtime(true);
		while (!$this->resolved) {
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