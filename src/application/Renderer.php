<?php

namespace application;

use phpDocumentor\Reflection\File\LocalFile;
use Symfony\Component\Console\Output\OutputInterface;
use phpDocumentor\Reflection\Php\ProjectFactory;

/**
 * Class Renderer. Main documentation renderer.
 *
 * @package application
 */
class Renderer {

  /**
   * Output path.
   *
   * @var string
   */
  private $outputPath;

  /**
   * Doc data array.
   *
   * @var array.
   */
  private $data;

  /**
   * Documentation class constructor.
   *
   * @param string
   *   Drupal module path.
   */
  public function __construct($outputPath, $data) {
    $this->$outputPath = $outputPath;
    $this->$data = $data;
  }

}
