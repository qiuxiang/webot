<?php

return array(
  'p1' => 'h1',
  'p2' => array(
    'news' => array(
      'title' => 'hello',
      'content' => 'world',
    ),
  ),
  'p(.*)3' => function () {
    return 'hello';
  }
);