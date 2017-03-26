<?php

namespace Board\Exception;

use Throwable;
use Exception;

class UnknownError extends Exception {

	public function __construct(Throwable $previous = null) {
		parent::__construct("", 500, $previous);
	}

}
