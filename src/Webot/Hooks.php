<?php namespace Wechat\Webot;

use Wechat\Request;
use Wechat\Response;

class Hooks {
  /**
   * @var array
   */
  private $hooks;

  /**
   * @var Request
   */
  private $request;

  /**
   * @var Response
   */
  private $response;

  /**
   * @param Request $request
   * @param Response $response
   */
  public function __construct($request, $response) {
    $this->request = $request;
    $this->response = $response;
  }

  /**
   * @param string $hook
   * @param callable $callback
   */
  public function register($hook, $callback) {
    $this->hooks[$hook][] = $callback;
  }

  /**
   * @param string $hook
   */
  public function dispatch($hook) {
    if (isset($this->hooks[$hook])) {
      foreach ($this->hooks[$hook] as $callback) {
        call_user_func($callback, $this->request, $this->response);
      }
    }
  }
}