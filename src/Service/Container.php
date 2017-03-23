<?php

namespace Board\Service;

use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

use Throwable;
use Exception;
use ReflectionClass;

class Container implements ContainerInterface {

	protected $vals = [];

	public function __construct() {
		$this->vals = [];
		$this->vals[get_class()] = $this;
	}

	/** {@inheritDoc} */
	public function has($id) {
		return isset($this->vals[$id]) || class_exists($id);
	}

	/** {@inheritDoc} */
	public function get($id) {
		if (isset($this->vals[$id])) {
			return $this->vals[$id];
		}

		if (!class_exists($id)) {
			throw new class
				extends Exception
				implements NotFoundExceptionInterface {};
		}

		try {
			$rcls = new ReflectionClass($id);
			$rcon = $rcls->getConstructor();
			$rpars = $rcon ? $rcon->getParameters() : [];

			$pars = [];
			foreach ($rpars as $i => $rpar) {
				if ($rpar->isDefaultValueAvailable()) {
					$pars[$i] = $rpar->getDefaultValue();
					continue;
				}

				if ($cls = $rpar->getClass()) {
					$cls = $cls->getName();
					$pars[$i] = $this->get($cls);
					continue;
				}

				$message = sprintf("can't recognize argument %s of class %s", $rpar->getName(), $id);
				throw new Exception($message);
			}

			return new $id(...$pars);
		} catch (Throwable $e) {
			throw new class("", 0, $e)
				extends Exception
				implements ContainerExceptionInterface {};
		}
	}

	/**
	 * Sets an entry of the container.
	 *
	 * @param string $id Identifier of the entry to look for.
	 * @param mixed $val Value to set.
	 *
	 * @throws ContainerExceptionInterface Error while setting a value.
	 *
	 * @return void
	 */
	public function set($id, $val) {
		$this->vals[$id] = $val;
	}

}
