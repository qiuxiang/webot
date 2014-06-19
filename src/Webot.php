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
    $this->hook = new Webot\Hooks(array(
      'request' => $this->wechat->request,
      'response' => $this->wechat->response,
    ));
    $this->hook->dispatch('init');
  }

  public function run() {
    if ($this->wechat->token && !$this->wechat->request->valid()) {
      $this->hook->dispatch('request.invalid');
      return;
    } elseif (isset($_GET['echostr'])) {
      echo $_GET['echostr'];
    }

    switch ($this->wechat->request->msgtype) {
      case 'text':
        $this->hook->dispatch('text.before', array(
          'content' => $this->wechat->request->content,
        ));
        $this->handler->matchs($this->rules);
        $this->hook->dispatch('text', array(
          'content' => $this->wechat->request->content,
        ));
        break;

      case 'image':
        $this->hook->dispatch('image', array(
          'picture' => $this->wechat->request->picurl,
          'mediaId' => $this->wechat->request->mediaId,
        ));
        break;

      case 'link':
        $this->hook->dispatch('link');
        break;

      case 'location':
        $this->hook->dispatch('location', array(
          'scale' => $this->wechat->request->scale,
          'label' => $this->wechat->request->label,
          'location' => array(
            'x' => $this->wechat->request->location_x,
            'y' => $this->wechat->request->location_y,
          ),
        ));
        break;

      case 'video':
        $this->hook->dispatch('video', array(
          'mediaId' => $this->wechat->request->mediaId,
          'thumbMediaId' => $this->wechat->request->thumbMediaId,
        ));
        break;

      case 'voice':
        $this->hook->dispatch('voice', array(
          'format' => $this->wechat->request->format,
          'mediaId' => $this->wechat->request->mediaId,
          'recognition' => $this->wechat->request->recognition,
        ));
        break;

      case 'event':
        switch ($this->wechat->request->event) {
          case 'subscribe':
            $this->hook->dispatch('event.subscribe', array(
              'eventKey' => $this->wechat->request->eventKey,
            ));
            break;

          case 'unsubscribe':
            $this->hook->dispatch('event.unsubscribe');
            break;

          case 'SCAN':
            $this->hook->dispatch('event.scan', array(
              'eventKey' => $this->wechat->request->eventKey,
              'ticket' => $this->wechat->request->ticket,
            ));
            break;

          case 'CLICK':
            $this->hook->dispatch('event.click.before', array(
              'eventKey' => $this->wechat->request->eventKey,
            ));
            $this->handler->equals($this->menus);
            $this->hook->dispatch('event.click', array(
              'eventKey' => $this->wechat->request->eventKey,
            ));
            break;

          case 'VIEW':
            $this->hook->dispatch('event.view', array(
              'eventKey' => $this->wechat->request->eventKey,
            ));
            $this->handler->equals($this->menus);
            break;

          default:
            $this->hook->dispatch('unknown.event');
        }
        break;

      default:
        $this->hook->dispatch('unknown.message');
    }

    $this->hook->dispatch('end', array(
      'handled' => $this->wechat->response->responded(),
    ));
  }

  /**
   * @param string $hook
   * @param callable $callback
   */
  public function on($hook, $callback) {
    $this->hook->register($hook, $callback);
  }
}
