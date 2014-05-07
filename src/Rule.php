<?php namespace Webot;

use ArrayIterator;
use IteratorAggregate;
use Symfony\Component\Yaml\Yaml;

class Rule implements IteratorAggregate {
  /**
   * @var array
   */
  protected $rules = array();

  /**
   * @return ArrayIterator
   */
  public function getIterator() {
    return new ArrayIterator($this->rules);
  }

  /**
   * @param array $rules array(pattern => handler)
   */
  public function add($rules) {
    $this->rules = array_merge($this->rules, $rules);
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
  protected function readYaml($file) {
    return Yaml::parse($file);
  }

  /**
   * @param string $file
   * @return array
   */
  protected function readJson($file) {
    return json_decode(file_get_contents($file), true);
  }

  /**
   * @param string $files
   * @param string $type json|yaml
   */
  public function loadFile($files, $type) {
    foreach (glob($files) as $file) {
      $this->add($this->{'read' . ucfirst($type)}($file));
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
