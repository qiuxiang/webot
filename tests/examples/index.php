<?php

include __DIR__ . '/../../vendor/autoload.php';

$webot = new Webot('token');
$webot->rules->loadPhp(__DIR__ . '/../fixtures/rule/rules.php');
$webot->run();