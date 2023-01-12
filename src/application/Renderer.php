<?php

namespace application;

use phpDocumentor\Reflection\File\LocalFile;
use Symfony\Component\Console\Output\OutputInterface;
use phpDocumentor\Reflection\Php\ProjectFactory;
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
   * Documentation class constructor.
   *
   * @param string
   *   Drupal module path.
   */
  public function __construct($outputPath, $data) {
    $this->templatesPath =
      \Phar::running() !== '' ? \Phar::running() . $this->templatesPath
        : getcwd() . '/templates/default';
    $this->templatesEngine = new Engine($this->templatesPath);
    $this->outputPath = $outputPath;
    $this->data = $data;
    $this->parseDown = new Parsedown();
  }

  /**
   * Write the output file.
   */
  public function write() {
    $contents = $this->templatesEngine->render('base_info', ['data' => $this->data]);
    if (!empty($this->data['entities'])) {
      $contents .= $this->templatesEngine->render('entities', ['data' => $this->data['entities']]);
    }

    #$contents .= $this->templatesEngine->render('routes', ['data' => $this->data]);
    #$contents .= $this->templatesEngine->render('services', ['data' => $this->data]);
    #$contents .= $this->templatesEngine->render('plugins', ['data' => $this->data]);

    file_put_contents($this->outputPath . 'output.md', $contents);
    $html = $this->parseDown->text($contents);
    file_put_contents($this->outputPath . 'output.html', $html);
  }

}
