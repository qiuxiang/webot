<?php

class WebotTest extends Wechat_TestCase {
  public $serverUrl = 'http://localhost:8001';
  public $fromUserName = 'client';
  public $toUserName = 'server';

  public function testConstructor() {
    print_r($this->send('text', array('content' => 'paa3')));
  }
}
