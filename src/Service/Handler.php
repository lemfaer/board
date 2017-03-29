<?php

namespace Board\Service;

use Throwable;
use Board\Exception\AccessDenied;
use Board\Exception\PageNotFound;

class Handler {

	protected $auth;
	protected $redirect;

	public function __construct(Auth $auth, Redirect $redirect) {
		$this->auth = $auth;
		$this->redirect = $redirect;
	}

	/**
	 * Register exception handler
	 * @return void
	 */
	public function register() : void {
		set_exception_handler([ $this, "handle" ]);
	}

	/**
	 * Handle exception
	 * @param Throwable $e exception
	 * @return void
	 */
	public function handle(Throwable $e) : void {
		if ($e instanceof AccessDenied) {
			if (!$this->auth->logged()) {
				($this->redirect)("login");
				die;
			}

			http_response_code(403);
			die;
		}

		if ($e instanceof PageNotFound) {
			http_response_code(404);
			die;
		}

		# ...log
		http_response_code(500);
		die;
	}

}
