<?php

namespace Board\Exception;

use Throwable;
use Exception;

class PageNotFound extends Exception {

	public function __construct(Throwable $previous = null) {
		parent::__construct("", 404, $previous);
	}

}
