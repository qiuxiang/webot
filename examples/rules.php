<?php

return [
  'php' => ':)',
  '(\d+)\s?\+\s?(\d+)' => function ($content, $a, $b) {
     return $a + $b;
  },
];