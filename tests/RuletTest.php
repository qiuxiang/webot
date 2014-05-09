<?php

class RuleTest extends PHPUnit_Framework_TestCase {
  public function testAdd() {
    $rule = new Webot_Rules;

    $rule->merge(array('p1' => 'h1', 'p2' => 'h2'));
    $rule->add('p3', 'h3');

    $this->assertAdded(array(
      'p1' => 'h1', 'p2' => 'h2', 'p3' => 'h3'), $rule);
  }

  public function testLoadFiles() {
    $rules = array(
      'y1' => 'h1',
      'y2' => 'h2',
      'y3' => array('a', 'b', 'c'),
      'j1' => 'h1',
      'j2' => array('a', 'b'),
      'ya1' => 'ha1',
      'yb1' => 'hb1',
      'ja1' => 'ha1',
      'jb1' => 'hb1',
      'p1' => 'h1',
      'p2' => array(
        'news' => array(
          'title' => 'hello',
          'content' => 'world',
        ),
      ),
    );

    $rule = new Webot_Rules;
    $rule->loadYaml(__DIR__ . '/fixtures/rule/rules.yml');
    $rule->loadYaml(__DIR__ . '/fixtures/rule/yaml/*.yml');
    $rule->loadJson(__DIR__ . '/fixtures/rule/rules.json');
    $rule->loadJson(__DIR__ . '/fixtures/rule/json/*.json');
    $rule->loadPhp(__DIR__ . '/fixtures/rule/rules.php');
    $this->assertAdded($rules, $rule);

    $rule = new Webot_Rules;
    $rule->loadYaml(array(
      __DIR__ . '/fixtures/rule/rules.yml',
      __DIR__ . '/fixtures/rule/yaml/a.yml',
      __DIR__ . '/fixtures/rule/yaml/b.yml',
    ));
    $rule->loadJson(array(
      __DIR__ . '/fixtures/rule/rules.json',
      __DIR__ . '/fixtures/rule/json/a.json',
      __DIR__ . '/fixtures/rule/json/b.json',
    ));
    $rule->loadPhp(array(
      __DIR__ . '/fixtures/rule/rules.php',
    ));
    $this->assertAdded($rules, $rule);
  }

  /**
   * @param array $expected
   * @param Webot_Rules $rule
   */
  public function assertAdded($expected, $rule) {
    $rules = array();

    foreach ($rule as $pattern => $handler) {
      $rules[$pattern] = $handler;
    }

    foreach ($expected as $pattern => $handler) {
      $this->assertEquals($handler, $rules[$pattern]);
    }
  }
}
