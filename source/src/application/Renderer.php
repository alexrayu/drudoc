<?php

namespace application;

use League\Plates\Engine;
use Parsedown;

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
   * Template engine.
   *
   * @var \League\Plates\Engine
   */
  private $templatesEngine;

/**
 * Parsedown class.
 *
 * @var Parsedown
 */
  private $parseDown;

  /**
   * Templates path.
   */
  private $templatesPath;

  /**
   * Documentation class constructor.
   *
   * @param string $outputPath
   *   Drupal module path.
   * @param array $data
   *   Detected data.
   */
  public function __construct($outputPath, array $data) {
    $this->templatesPath =
      \Phar::running() !== '' ? \Phar::running() . '/templates'
        : getcwd() . '/templates';
    $this->templatesEngine = new Engine($this->templatesPath);
    $this->outputPath = $outputPath;
    $this->data = $data;
    $this->parseDown = new \Parsedown();
  }

  /**
   * Write the output file.
   */
  public function write() {
    $contents = $this->templatesEngine->render('base_info', ['data' => $this->data]);

    // Entity types.
    if (!empty($this->data['entities'])) {
      $contents .= "\n";
      $contents .= $this->templatesEngine->render('entities', ['data' => $this->data['entities']]);
    }

    // Plugins.
    if (!empty($this->data['plugins'])) {
      $contents .= "\n";
      $contents .= $this->templatesEngine->render('plugins', ['data' => $this->data['plugins']]);
    }

    file_put_contents($this->outputPath . '/output.md', $contents);
    $html = $this->parseDown->text($contents);
    file_put_contents($this->outputPath . '/output.html', $html);
  }

}
