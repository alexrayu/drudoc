<?php

namespace application;

/**
 * Class Doc. Main documentation handler.
 *
 * @package application
 */
class Doc {

  /**
   * Main documentation array.
   *
   * @var array
   */
  private $doc = [];

  /**
   * Drupal module folder.
   *
   * @var string
   */
  private $inputPath;

  /**
   * @var FilesTreatment object.
   */
  private $filesTreatment;

  /**
   * Detected name of the source module.
   *
   * @var string
   */
  private $modulename;

  /**
   * Yamls to load to get data from.
   *
   * @var string[]
   */
  private $yamls = ['info', 'libraries', 'links.menu', 'routing', 'services'];

  /**
   * Documentation class constructor.
   *
   * @param string
   *   Drupal module path.
   */
  public function __construct($inputPath, $filesTreatment) {
    $this->inputPath = $inputPath;
    $this->filesTreatment = $filesTreatment;
    $this->doc = [
      '_pathinfo' => pathinfo($inputPath),
    ];
    $this->modulename = $this->doc['_pathinfo']['filename'] ?? '';
    if (!$this->modulename) {
      throw new \Exception('Could not detect the module at the specified location.');
    }
    $this->getYamlsInfo();
    $this->getEntities();
    $this->getPlugins();
  }

  /**
   * Getter for the documentation array.
   *
   * @return array
   *   Documentation array.
   */
  public function getDoc() {
    return $this->doc;
  }

  /**
   * Processes the entitytype definitions.
   */
  protected function getEntities() {
    $files = $this->filesTreatment->findFilesRecursive($this->inputPath . 'src/Entity', ['php']);

    $this->doc['entities'] = [];
    foreach ($files as $file) {
      $info = $this->filesTreatment->getClassInfo($file);
      if ($info) {
        $this->doc['entities'][] = $info;
      }
    }
  }

  /**
   * Processes the plugins definitions.
   */
  protected function getPlugins() {
    $files = $this->filesTreatment->findFilesRecursive($this->inputPath . 'src/Plugin', ['php']);

    $this->doc['plugins'] = [];

    // Get plugins.
    $plugins = [];
    foreach ($files as $file) {
      $info = $this->filesTreatment->getClassInfo($file);
      if ($info) {
        $plugins[] = $info;
      }
    }

    // Group plugins.
    foreach ($plugins as $plugin) {
      $this->doc['plugins'][$plugin['plugin_type']][] = $plugin;
    }
  }

  /**
   * Gets the main yamls information from the module path.
   *
   * @return bool
   *   Whether the basic info was loaded succesfully.
   */
  protected function getYamlsInfo() {
    try {
      $modulename = $this->modulename;
      if (!$modulename) {
        return FALSE;
      }
      $basic = [];
      foreach ($this->yamls as $name) {
        $data = $this->filesTreatment->getYaml($this->inputPath . "$modulename.$name.yml");
        if (!empty($data)) {
          $basic[$name] = $data;
        }
      }
      $this->doc['yml'] = $basic;
    }
    catch (\Exception $e) {
      return FALSE;
    }

    return TRUE;
  }

}
