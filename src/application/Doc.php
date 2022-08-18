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
   * Documentation class constructor.
   *
   * @param string
   *   Drupal module path.
   */
  public function __construct($inputPath, $filesTreatment) {
    $this->inputPath = $inputPath;
    $this->filesTreatment = $filesTreatment;
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
   * Gets the basic information from the module path.
   *
   * @return bool
   *   Whether the basic info was loaded succesfully.
   */
  public function getBasicInfo() {
    try {
      $this->doc['basic'] = $this->filesTreatment->getYaml($this->inputPath, '*.info.yml');
      if (!empty($this->doc['basic'])) {
        return TRUE;
      }
    }
    catch (\Exception $e) {}

    return FALSE;
  }

}
