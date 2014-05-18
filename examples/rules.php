<?php

return [
  'php' => ':)',
  '(\d+)\s?\+\s?(\d+)' => function ($matchs) {
     return $matchs[0] + $matchs[1];
  },
];