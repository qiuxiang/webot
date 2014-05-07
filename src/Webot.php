<?php namespace Webot;

class Webot {
  /**
   * @var string
   */
  public $token;

  /**
   * @param string $token
   */
  public function __construct($token='') {
    $this->rule = new Rule;
    $this->wechat = new \Wechat($token);
  }

  public function run() {
    ;
  }
}
