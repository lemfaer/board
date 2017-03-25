<?php

namespace Board\Service;

class View {

	protected $conf;

	public function __construct(Config $conf) {
		$this->conf = $conf;
	}

	public function __invoke(...$args) {
		return $this->view(...$args);
	}

	/**
	 * Displays a view
	 * @param string $name name of view
	 * @param array $params view params
	 * @return string generated view
	 */
	public function view(string $name, array $params = []) : string {
		$rel = str_replace(".", DIRECTORY_SEPARATOR, $name);
		$abs = implode(DIRECTORY_SEPARATOR, [ $this->conf["app"]["view"], $rel ]);
		$html = "$abs.html";

		extract($params);
		ob_start();
		include $html;
		return ob_get_clean();
	}

}
