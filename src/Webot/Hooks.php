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
   */
  public function dispatch($hook) {
    if (isset($this->hooks[$hook])) {
      foreach ($this->hooks[$hook] as $callback) {
        $callback($this->depends);
      }
    }
  }
}