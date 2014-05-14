<?php

include __DIR__ . '/../vendor/autoload.php';

$webot = new Wechat\Webot('token');
$webot->rules->add('hello', 'world');
$webot->run();