<?php

namespace Board\Service;

class Config extends ArrayObject {

	public function __construct($conf = []) {
		parent::__construct($conf);
	}

}
