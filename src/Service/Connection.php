<?php

namespace Board\Service;

use PDO;

class Connection extends PDO {

	public function __construct(Config $conf) {
		$host = $conf["db"]["host"];
		$base = $conf["db"]["base"];
		$user = $conf["db"]["user"];
		$pass = $conf["db"]["pass"];
		$opts = $conf["db"]["options"];

		$dsn = "mysql:host=$host;dbname=$base";
		parent::__construct($dsn, $user, $pass, $opts);
	}

}
