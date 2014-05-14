<?php

class WebotTest extends Wechat\TestCase {
  public $serverUrl = 'http://localhost:8001';
  public $fromUserName = 'client';
  public $toUserName = 'server';
  public $token = 'token';

  public function testConstructor() {
    print_r($this->send('text', 'hello'));
  }
}
