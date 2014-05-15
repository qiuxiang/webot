<?php namespace Wechat;

class Webot {
  /**
   * text rules
   *
   * @var Webot\Rules
   */
  public $rules;

  /**
   * click rules
   *
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
   * @var Webot\Hook
   */
  public $hook;

  /**
   * @var Request
   */
  public $request;

  /**
   * @var Response
   */
  public $response;

  /**
   * @param string $token
   */
  public function __construct($token='') {
    $this->rules = new Webot\Rules;
    $this->menus = new Webot\Rules;
    $this->wechat = new Wechat($token);
    $this->request = $this->wechat->request;
    $this->response = $this->wechat->response;
    $this->handler = new Webot\Handler($this->wechat);
    $this->hook = new Webot\Hook($this->request, $this->response);
    $this->hook->dispatch('init');
  }

  public function run() {
    switch ($this->wechat->request->msgtype) {
      case 'text':
        $this->hook->dispatch('text');
        $this->handler->matchs($this->rules);
        break;

      case 'event':
        switch ($this->wechat->request->event) {
          case 'subscribe':
            $this->hook->dispatch('event.subscribe');
            break;

          case 'CLICK':
            $this->hook->dispatch('event.click');
            $this->handler->equals($this->menus);
            break;

          default:
            $this->hook->dispatch('event.unknown');
        }
        break;

      default:
        $this->hook->dispatch('message.unknown');
    }

    $this->hook->dispatch('end');
  }

  /**
   * @param string $hook
   * @param callable $callback
   */
  public function on($hook, $callback) {
    $this->hook->register($hook, $callback);
  }
}
