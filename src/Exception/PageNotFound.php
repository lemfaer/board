<?php

namespace Board\Exception;

use Throwable;
use Exception;

class PageNotFound extends Exception {

	public function __construct(string $message = "", Throwable $previous = null) {
		parent::__construct($message, 404, $previous);
	}

}
