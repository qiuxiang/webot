<?php

include __DIR__ . '/../vendor/autoload.php';

$webot = new Wechat\Webot('token');
$webot->rules->add('hello', 'world');
$webot->rules->loadPhp('rules.php');
$webot->rules->loadYaml('rules.yml');
$webot->rules->loadJson('rules.json');
$webot->on('end', function ($depends) {
  $depends->response->text('welcome');
});
$webot->run();
