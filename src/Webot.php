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
  private $handler;

  /**
   * @var Webot\Hooks
   */
  private $hook;

  /**
   * @param string $token
   */
  public function __construct($token='') {
    $this->rules = new Webot\Rules;
    $this->menus = new Webot\Rules;
    $this->wechat = new Wechat($token);
    $this->handler = new Webot\Handler($this->wechat);
    $this->hook = new Webot\Hooks([
      'request' => $this->wechat->request,
      'response' => $this->wechat->response,
    ]);
    $this->hook->dispatch('init');
  }

  public function run() {
    switch ($this->wechat->request->msgtype) {
      case 'text':
        $this->hook->dispatch('text');
        $this->handler->matchs($this->rules);
        break;

      case 'image':
        $this->hook->dispatch('image');
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
            $this->hook->dispatch('unknown.event');
        }
        break;

      default:
        $this->hook->dispatch('unknown.message');
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
