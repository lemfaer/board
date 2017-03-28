<?php

namespace Board\Service;

class Message {

	protected $conf;
	protected $session;

	public function __construct(Config $conf, Session $session) {
		$this->conf = $conf;
		$this->session = $session;
	}

	public function __invoke(...$args) {
		return $this->message(...$args);
	}

	/**
	 * Push config message to user
	 * @param string $name message config name
	 * @return void
	 */
	public function message(string $name) : void {
		$this->text($this->conf["messages"][$name]);
	}

	/**
	 * Push text message to user
	 * @param string $text
	 * @return void
	 */
	public function text(string $text) : void {
		$messages = $this->session["messages"] ?? [];
		$messages[] = $text;
		$this->session["messages"] = $messages;
	}

	/**
	 * Message(s) exists
	 * @return bool
	 */
	public function exists() {
		return !empty($session["messages"]);
	}

	/**
	 * Pop one message from storage
	 * @return string|null
	 */
	public function pop() {
		$messages = $this->session["messages"] ?? [];
		$message = array_pop($messages);
		$this->session["messages"] = $messages;
		return $message;
	}

	/**
	 * Get all messages
	 * @return array
	 */
	public function all() : array {
		return $this->session["messages"] ?? [];
	}

	/**
	 * Flush all messages
	 * @return void
	 */
	public function flush() : void {
		unset($this->session["messages"]);
	}

}
