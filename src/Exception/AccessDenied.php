<?php

namespace Board\Exception;

use Throwable;
use Exception;

class AccessDenied extends Exception {

	public function __construct(string $message = "", Throwable $previous = null) {
		parent::__construct($message, 403, $previous);
	}

}
