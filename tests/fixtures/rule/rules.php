<?php

return [
  'p1' => 'h1',
  'p2' => [
    'news' => [
      'title' => 'hello',
      'content' => 'world',
    ],
  ],
  'p(.*)3' => function () {
    return 'hello';
  }
];