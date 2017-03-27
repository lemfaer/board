<?php

namespace Board\Controller;

use Board\Model\Appointment\Month;

class Test extends Controller {

	/** /testc */
	public function test() {
		$month = new Month($this->conf, $this->connection);
		$month->load("2017-3");
		echo "<pre>";
		print_r($month->prepare());
		echo "</pre>";
	}

}
