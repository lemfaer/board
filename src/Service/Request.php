<?php

namespace Board\Service;

use ArrayObject;

class Request extends ArrayObject {

	public function __construct(string $method = null, array $params = null) {
		if ($method === null) {
			$method = $_SERVER["REQUEST_METHOD"];
		}

		if ($params === null) {
			$params = $_REQUEST;
		}

		parent::__construct($params);
		$this["method"] = $method;
	}

}
