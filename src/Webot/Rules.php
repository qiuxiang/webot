<?php

use Symfony\Component\Yaml\Yaml;

class Webot_Rules implements IteratorAggregate {
  /**
   * @var array
   */
  public $rules = array();

  /**
   * @return ArrayIterator
   */
  public function getIterator() {
    return new ArrayIterator($this->rules);
  }

  /**
   * @param string $pattern
   * @param callable|string $handler
   */
  public function add($pattern, $handler) {
    $this->rules[$pattern] = $handler;
  }

  /**
   * @param array $rules array(pattern => handler)
   */
  public function merge($rules) {
    $this->rules = array_merge($this->rules, $rules);
  }

  /**
   * @param string|array $files
   */
  public function loadPhp($files) {
    $this->loadFiles($files, 'php');
  }

  /**
   * @param string|array $files
   */
  public function loadYaml($files) {
    $this->loadFiles($files, 'yaml');
  }

  /**
   * @param string|array $files
   */
  public function loadJson($files) {
    $this->loadFiles($files, 'json');
  }

  /**
   * @param string $file
   * @return array
   */
  public function readPhp($file) {
    return include $file;
  }
  /**
   * @param string $file
   * @return array
   */
  public function readYaml($file) {
    return Yaml::parse($file);
  }

  /**
   * @param string $file
   * @return array
   */
  public function readJson($file) {
    return json_decode(file_get_contents($file), true);
  }

  /**
   * @param string $files
   * @param string $type json|yaml
   */
  public function loadFile($files, $type) {
    foreach (glob($files) as $file) {
      $this->merge($this->{'read' . ucfirst($type)}($file));
    }
  }

  /**
   * @param string|array $files
   * @param string $type json|yaml
   */
  public function loadFiles($files, $type) {
    if (is_string($files)) {
      $this->loadFile($files, $type);
    }

    if (is_array($files)) {
      foreach ($files as $file) {
        $this->loadFile($file, $type);
      }
    }
  }
}
