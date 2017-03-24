<?php

namespace Board\Service;

use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

use Throwable;
use Exception;
use ArrayObject;
use ReflectionClass;

class Container extends ArrayObject implements ContainerInterface {

	public function __construct(array $vals = []) {
		parent::__construct($vals);
		$this[get_class()] = $this;
	}

	/** {@inheritDoc} */
	public function has($id) {
		return isset($this[$id]) || class_exists($id);
	}

	/** {@inheritDoc} */
	public function get($id) {
		if (isset($this[$id])) {
			return $this[$id];
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

			$obj = new $id(...$pars);
			$this[$id] = $obj;
			return $obj;
		} catch (Throwable $e) {
			throw new class("", 0, $e)
				extends Exception
				implements ContainerExceptionInterface {};
		}
	}

}
