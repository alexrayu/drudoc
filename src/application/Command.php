<?php

namespace application;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require 'FilesTreatment.php';
require 'Doc.php';
require 'Renderer.php';

/**
 * Class Command
 *
 * @package application
 */
class Command extends SymfonyCommand {

  /**
   * Command constructor.
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Main command to create docs.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function createDocs(
    InputInterface $input,
    OutputInterface $output
  ): void {
    $inputPath = $input->getArgument('inputPath');
    $outputPath = $input->getArgument('outputPath');
    if ($inputPath[-1] !== '/') {
      $inputPath .= '/';
    }
    try {
      $filesTreatment = new FilesTreatment($inputPath);
      $doc = new Doc($inputPath, $filesTreatment);
      $data = $doc->getDoc();
      $renderer = new Renderer($outputPath, $data);
      $renderer->write();

    }
    catch (\Exception $e) {
      $output->writeln('Documentation could not be created: ' . $e->getMessage());
    }
  }

}
