微信开发开发框架
=============
Webot 旨在提供简单的方式搭建灵活的、易于扩展的微信公众平台，其灵感来自于 [weixin-robot](https://github.com/node-webot/weixin-robot)。

基本用法
-------
```php
$webot = new Wechat\Webot('token');

// 建立 hello => world 规则
$webot->rules->add('hello', 'world');

// 使用正则表达式
$webot->rules->add('(c|course).*(\d+)', 'some course');

// 如果 handler 是数组，则随机回复一条消息
$webot->rules->add('rand', ['1', '2', '3']);

// 订阅事件处理
$webot->on('event.subscribe', function ($depends) {
  $depends['response']('text', 'welcome');
});

$webot->run();
```

添加规则
-------
`rules->add(string $pattern, mixed $handler)`

### 回复更多形式的消息
```php
// 回复单图文消息
$webot->rules->add('what', ['news' => [
  'title' => '标题',
  'content' => '描述',
  'picture' => 'http://example.com/picture.jpg',
  'url' => 'http://example.com',
]]);

// 回复多图文消息
$webot->rules->add('what', ['news' => [
  [
    'title' => '标题1',
    'picture' => 'http://example.com/picture1.jpg',
    'url' => 'http://example.com/1',
  ],
  [
    'title' => '标题2',
    'picture' => 'http://example.com/picture2.jpg',
    'url' => 'http://example.com/2',
  ],
]);
```

### 使用函数进行处理
```php
// 回复文本消息
$webot->rules->add('hello', function () {
  return 'world';
});

// 回复图文消息
$webot->rules->add('what', function () {
  return [
    'news' => [
      'title' => '标题',
      'content' => '描述',
      'picture' => 'http://example.com/picture.jpg',
      'url' => 'http://example.com',
    ]
  ];
});

// 当 pattern 为正则表达式时，matchs 会作为函数的参数传入
$webot->rules->add('(1)+(2)', function ($matchs) {
  return $matchs[1] + $matchs[2]; // 3
});
```

### 从 PHP、YAML 或 JSON 文件加载规则
```php
$webot->rules->loadPhp('rules.php');
$webot->rules->loadJson('rules.json');
$webot->rules->loadYaml('rules.yml');

// 同时加载多个文件
$webot->rules->loadJson([
  'rules1.json',
  'rules2.json',
]);

// glob 表达式
$webot->rules->loadYaml('rules/yaml/*.yml');
```

rules.php
```php
<?php

return [
  'pattern' => 'h1',
  'test' => [
    'news' => [
      'title' => 'hello',
      'content' => 'world',
    ],
  ],
  'p(.*)3' => function () {
    return 'hello';
  }
];
```

rules.json
```json
{
  "hello": "world",
  "hi": ["a", "b"]
}
```

rules.yml
```yaml
c(\d+): h1
good:
  news:
    title: 标题
    content: 描述
y3:
  - a
  - b
  - c
```

菜单事件处理
----------
菜单事件的处理方式与文本规则一样，只不过 `$pattern` 为定值的 `EventKey`，并且与 `$rules` 区分使用 `$menus`
```php
$webot->$menus->add('新闻', ['news' => [
  [
    'title' => '标题1',
    'picture' => 'http://example.com/1.jpg',
    'url' => 'http://example.com/news/1',
  ],
  [
    'title' => '标题2',
    'picture' => 'http://example.com/2.jpg',
    'url' => 'http://example.com/news/2',
  ],
]])

$webot->$menus->loadPhp('menus.php');
```

事件处理
------
`on(string $hook, callable $callback)`

$callback 会以数组形式传入依赖，其中包含了请求信息（request），和回复函数（reponse）

支持的事件列表:
- init - 初始化时运行
- event - 收到事件消息
- event.click - 菜单点击事件
- event.subscribe - 订阅事件
- event.unsubscribe - 取消订阅事件
- text - 收到文本消息
- image - 收到图片消息
- unknown.message - 未知的消息类型
- unknown.event - 未知的事件类型

```php
$webot->on('event.click', function ($depends) {
  // $request->eventKey 事件代码
  // $request->fromUserName 用户 OpenID
  $depends['response']('text', 'hello');
});
```
