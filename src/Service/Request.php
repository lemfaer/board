<?php

namespace Board\Service;

use ArrayObject;

class Request extends ArrayObject {

	public function __construct($request = null) {
		if ($request === null) {
			$request = $_REQUEST;
		}

		parent::__construct($request);
	}

}
