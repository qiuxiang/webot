<?php namespace Wechat\Webot;

use Wechat\Wechat;

class Handler {
  /**
   * @var Wechat
   */
  public $wechat;

  /**
   * @param Wechat $wechat
   */
  public function __construct($wechat) {
    $this->wechat = $wechat;
  }

  /**
   * @param mixed $handler
   * @param array $params
   */
  public function execute($handler, $params=array()) {
    if (is_array($handler) && isset($handler[0])) {
      $handler = array_rand($handler);
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

  /**
   * @param Rules $rules
   */
  public function matchs($rules) {
    foreach ($rules as $pattern => $handler) {
      preg_match('/' . $pattern . '/i',
        $this->wechat->request->content, $matches);

      if (!empty($matches)) {
        $this->execute($handler, $matches);
      }
    }
  }

  /**
   * @param Rules $rules
   */
  public function equals($rules) {
    foreach ($rules as $key => $handler) {
      if ($key == $this->wechat->request->eventKey) {
        $this->execute($handler);
      }
    }
  }
}
