<?php

namespace Board\Service;

class Router {

	protected $routes;
	protected $container;

	public function __construct(Container $container, Config $conf) {
		$this->routes = $conf["routes"];
		$this->container = $container;
	}

	/**
	 * Dispatches against the provided HTTP method verb and URI.
	 *
	 * @param string $method
	 * @param string $uri
	 *
	 * @return mixed
	 */
	public function dispatch(string $method, string $uri) {
		$routes = array_filter($this->routes, function ($route) use ($method, $uri) {
			[ $rmethod, $rpattern ] = $route;
			return $rmethod === $method && preg_match($rpattern, $uri) === 1;
		});

		[ $method, $pattern, [ $class, $method ] ] = array_shift($routes);
		preg_match($pattern, $uri, $params);
		array_shift($params);

		return $this->container->get($class)->$method(...$params);
	}

}
