<?php

namespace application;

require 'MdFile.php';

use application\MdFile;
use phpDocumentor\Reflection\File\LocalFile;
use Symfony\Component\Console\Output\OutputInterface;
use phpDocumentor\Reflection\Php\ProjectFactory;
use Symfony\Component\Yaml\Yaml;

/**
 * Class FilesTreatment
 *
 * @package application
 */
class FilesTreatment {

  /**
   * @var \phpDocumentor\Reflection\File\LocalFile
   */
  private $files = [];

  /**
   * FilesTreatment constructor.
   *
   * @param string $fileOrDir
   *
   * @throws \Exception if $fileOrDir is not file or directory.
   */
  public function __construct(string $fileOrDir) {
    if (is_file($fileOrDir)) {
      $this->files[] = new LocalFile($fileOrDir);
    }
    else if (is_dir($fileOrDir)) {
      $this->files = self::filesFromDirectory($fileOrDir);
    }
    else {
      throw new \Exception('Input should be a file or a directory.');
    }
  }

  /**
   * @param string $directory
   * @param string[] $results
   *
   * @return \phpDocumentor\Reflection\File\LocalFile[]
   */
  private static function filesFromDirectory(
    string $directory,
    array &$results = []
  ): array {
    $files = scandir($directory);
    foreach ($files as $value) {
      $path = realpath($directory . DIRECTORY_SEPARATOR . $value);
      if (!is_dir($path)) {
        if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
          $results[] = new LocalFile($path);
        }
      }
      elseif ($value !== '.' && $value !== '..') {
        self::filesFromDirectory($path, $results);
      }
    }
    return $results;
  }

  /**
   * @param \Symfony\Component\Console\Output\OutputInterface $output
   * @param string $outputPath
   *
   * @throws \phpDocumentor\Reflection\Exception
   */
  public function createDocs(
    OutputInterface $output,
    string $outputPath
  ): void {
    $projectFactory = ProjectFactory::createInstance();
    $project = $projectFactory->create('Project to document', $this->files);
    foreach ($project->getFiles() as $file) {
      foreach ($file->getClasses() as $class) {
        $mdFile = new MdFile($outputPath . '/' . $class->getName());
        $output->writeln('Documenting class ' . $class->getName());
        $mdFile->createFromClass($class);
      }
      foreach ($file->getInterfaces() as $interface) {
        $mdFile = new MdFile($outputPath . '/' . $interface->getName());
        $output->writeln('Documenting interface ' . $interface->getName());
        $mdFile->createFromInterface($interface);
      }
      foreach ($file->getTraits() as $trait) {
        $mdFile = new MdFile($outputPath . '/' . $trait->getName());
        $output->writeln('Documenting trait ' . $trait->getName());
        $mdFile->createFromTrait($trait);
      }
    }
  }

  /**
   * Getter for files.
   *
   * @return array
   */
  public function getFiles() {
    return $this->files;
  }

  /**
   * Search for files by extension.
   *
   * @param array $extensions
   *   Extensions to scan for.
   *
   * @return array
   *   Found files.
   */
  public function findFiles($path, array $extensions = []) {
    $directory = new \RecursiveDirectoryIterator($path);
    $iterator = new \RecursiveIteratorIterator($directory);
    $files = [];

    foreach ($extensions as $extension) {
      $regex = new \RegexIterator($iterator, '/^.+\.' . $extension . '$/i', \RecursiveRegexIterator::GET_MATCH);
      foreach ($regex as $item) {
        $files[] = reset($item);
      }
    }

    return $files;
  }

  /**
   * Get data from yaml file by pappern.
   *
   * @param string $pattern
   *   Pattern to scan for.
   *
   * @return array
   *   Found data.
   */
  public function getYaml($path, $pattern) {
    $files = $this->findFiles($path, [$pattern]);
    if (!empty($files)) {
      return Yaml::parse(file_get_contents(reset($files)));
    }

    return [];
  }

}
