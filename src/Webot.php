<?php namespace Wechat;

class Webot {
  /**
   * @param string $token
   */
  public function __construct($token='') {
    $this->rules = new Webot\Rules;
    $this->menus = new Webot\Rules;
    $this->wechat = new Wechat($token);
  }

  /**
   * @param mixed $handler
   * @param array $params
   */
  public function handle($handler, $params=array()) {
    if (is_array($handler) && isset($handler[0])) {
      $handler = $handler[rand(0, count($handler) - 1)];
    }

    if (is_callable($handler)) {
      $handler = $handler($params);
    }

    $type = 'text';
    $data = $handler;

    if (is_array($handler)) {
      $type = key($handler);
      $data = $handler[$type];
    }

    $this->wechat->response->{$type}($data);
  }

  public function handleText() {
    foreach ($this->rules as $pattern => $handler) {
      preg_match('/' . $pattern . '/i',
        $this->wechat->request->content, $matches);

      if (!empty($matches)) {
        $this->handle($handler, $matches);
      }
    }
  }

  public function handleClick() {
    foreach ($this->menus as $key => $handler) {
      if ($key == $this->wechat->request->eventKey) {
        $this->handle($handler);
      }
    }
  }

  public function handleEvent() {
    switch ($this->wechat->request->event) {
      case 'subscribe':
        break;

      case 'CLICK':
        $this->handleClick();
        break;
    }
  }

  public function run() {
    switch ($this->wechat->request->msgtype) {
      case 'text':
        $this->handleText();
        break;

      case 'event':
        $this->handleEvent();
        break;
    }
  }
}
