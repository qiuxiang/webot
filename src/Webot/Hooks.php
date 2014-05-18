<?php namespace Wechat\Webot;

class Hooks {
  /**
   * @var array
   */
  private $hooks;

  /**
   * @var array
   */
  private $depends;

  /**
   * @param array $depends
   */
  public function __construct($depends) {
    $this->depends = $depends;
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
   * @param array $params
   */
  public function dispatch($hook, $params=[]) {
    if (isset($this->hooks[$hook])) {
      foreach ($this->hooks[$hook] as $callback) {
        $callback(array_merge($this->depends, $params));
      }
    }
  }
}