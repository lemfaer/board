<?php

namespace Board\Service;

use ArrayObject;

class Config extends ArrayObject {

	public function __construct(array $conf = []) {
		parent::__construct($conf);
	}

}
