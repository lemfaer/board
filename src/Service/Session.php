<?php

namespace Board\Service;

use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

class Session implements IteratorAggregate, ArrayAccess, Countable {

	public function __construct() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
	}

	public function getIterator() {
		return new ArrayIterator($_SESSION);
	}

	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$_SESSION[] = $value;
		} else {
			$_SESSION[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($_SESSION[$offset]);
	}

	public function offsetUnset($offset) {
		unset($_SESSION[$offset]);
	}

	public function offsetGet($offset) {
		return $_SESSION[$offset] ?? null;
	}

	public function count() {
		return count($_SESSION);
	}

}
