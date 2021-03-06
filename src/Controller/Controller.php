<?php

namespace Board\Controller;

use Board\Service\Auth;
use Board\Service\View;
use Board\Service\Config;
use Board\Service\Message;
use Board\Service\Request;
use Board\Service\Session;
use Board\Service\Redirect;
use Board\Service\Container;
use Board\Service\Connection;

class Controller {

	protected $auth;
	protected $conf;
	protected $view;
	protected $message;
	protected $request;
	protected $session;
	protected $redirect;
	protected $container;
	protected $connection;

	public function __construct(Auth $auth, Config $conf, Connection $connection,
			Container $container, Message $message, Redirect $redirect, Request $request,
			Session $session, View $view) {

		$this->auth = $auth;
		$this->conf = $conf;
		$this->view = $view;
		$this->message = $message;
		$this->request = $request;
		$this->session = $session;
		$this->redirect = $redirect;
		$this->container = $container;
		$this->connection = $connection;
	}

	protected function validate_token() {
		return true;
	}

}
