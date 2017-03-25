<?php

namespace Board\Service;

class Redirect {

	protected $conf;

	public function __construct(Config $conf) {
		$this->conf = $conf;
	}

	public function __invoke(...$args) {
		return $this->redirect(...$args);
	}

	/**
	 * Redirects to link (relative or other http)
	 * @param string $link
	 */
	public function redirect(string $link) : void {
		if (strpos($link, "http") === false) {
			$domain = $conf["app"]["domain"];
			$link = ltrim($link);
			$link = implode("/", [ $domain, $link ]);
		}

		header("Location: $link");
		die;
	}

}
