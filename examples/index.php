<?php

include __DIR__ . '/../vendor/autoload.php';

$webot = new Wechat\Webot('token');
$webot->rules->add('hello', 'world');
$webot->on('end', function ($request, $response) {
  print_r($request);
  $response('text', 'hello');
});
$webot->run();