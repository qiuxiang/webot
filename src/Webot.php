<?php namespace Wechat;

class Webot {
  /**
   * @var Webot\Rules
   */
  public $rules;

  /**
   * @var Webot\Rules
   */
  public $menus;

  /**
   * @var Wechat
   */
  public $wechat;

  /**
   * @var Webot\Handler
   */
  public $handler;

  /**
   * @param string $token
   */
  public function __construct($token='') {
    $this->rules = new Webot\Rules;
    $this->menus = new Webot\Rules;
    $this->wechat = new Wechat($token);
    $this->handler = new Webot\Handler($this->wechat);
  }

  public function eventHandler() {
    switch ($this->wechat->request->event) {
      case 'subscribe':
        break;

      case 'CLICK':
        $this->handler->equals($this->menus);
        break;
    }
  }

  public function run() {
    switch ($this->wechat->request->msgtype) {
      case 'text':
        $this->handler->matchs($this->rules);
        break;

      case 'event':
        $this->eventHandler();
        break;
    }
  }
}
