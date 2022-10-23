<?php

namespace application;

use phpDocumentor\Reflection\File\LocalFile;
use Symfony\Component\Console\Output\OutputInterface;
use phpDocumentor\Reflection\Php\ProjectFactory;

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
    $this->getYamlsInfo();
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
   * Gets the main yamls information from the module path.
   *
   * @return bool
   *   Whether the basic info was loaded succesfully.
   */
  protected function getYamlsInfo() {
    try {
      $basename = $this->doc['_pathinfo']['filename'] ?? '';
      if (!$basename) {
        return FALSE;
      }
      $basic = [];
      foreach ($this->yamls as $name) {
        $data = $this->filesTreatment->getYaml($this->inputPath . "$basename.$name.yml");
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
