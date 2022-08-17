<?php

namespace application;

use League\Plates\Engine;
use phpDocumentor\Reflection\Php\Interface_;
use phpDocumentor\Reflection\Php\Trait_;

/**
 * Class that generates the markdown output.
 *
 * @package create_documentation
 */
class MdFile {

  /**
   * @var string
   */
  private $templatesPath = '/templates/default';

  /**
   * @var \League\Plates\Engine
   */
  private $templatesEngine;

  /**
   * @var string
   */
  private $path;

  /**
   * @var string
   */
  private $html = '';

  /**
   * MdFile constructor.
   *
   * @param string $path
   */
  public function __construct(string $path) {
    $this->templatesPath =
      \Phar::running() !== '' ? \Phar::running() . $this->templatesPath
        : '../../templates/default';
    $this->path = $path;
    $this->templatesEngine = new Engine($this->templatesPath);
    $this->simpleTables = TRUE;
  }

  /**
   * @param \phpDocumentor\Reflection\Php\Class_ $class
   */
  public function createFromClass(\phpDocumentor\Reflection\Php\Class_ $class
  ): void {
    $parsedown = new \Parsedown();
    $summary =
      $class->getDocBlock() !== NULL ? $class->getDocBlock()->getSummary() : '';
    $description =
      $class->getDocBlock() !== NULL ? $parsedown->text($class->getDocBlock()
        ->getDescription()) : '';
    $this->writeHeader($class->getName(), $summary, $description);
    $this->writeSummary($class->getConstants(), $class->getProperties(),
      $class->getMethods());
    $this->writeConstants($class->getConstants());
    $this->writeProperties($class->getProperties());
    $this->writeMethods($class->getMethods());
    $this->create();
  }

  /**
   * @param string $name
   * @param string $summary
   * @param string $description
   */
  private function writeHeader(
    string $name,
    string $summary,
    string $description
  ): void {
    $this->WriteHTML(file_get_contents($this->templatesPath . '/styles.css'));
    $this->SetHeader('Generated with <a href="https://github.com/lluiscamino/phpDoc2pdf">phpDoc2pdf</a>');
    $this->html .= $this->templatesEngine->render('header',
      ['name' => $name, 'summary' => $summary, 'description' => $description]);
  }

  /**
   * @param \phpDocumentor\Reflection\Php\Constant[] $constants
   * @param \phpDocumentor\Reflection\Php\Property[] $properties
   * @param \phpDocumentor\Reflection\Php\Method[] $methods
   */
  private function writeSummary(
    array $constants,
    array $properties,
    array $methods
  ): void {
    $constants_ = $properties_ = $methods_ = [
      'public' => '',
      'protected' => '',
      'private' => '',
    ];
    foreach ($constants as $constant) {
      $constants_['public'] .= '<a href="#constant:' . $constant->getName()
        . '">' . $constant->getName() . '</a><br>';
    }
    foreach ($properties as $property) {
      $properties_[strval($property->getVisibility())] .= '<a href="#property:'
        . $property->getName() . '">$' . $property->getName() . '</a><br>';
    }
    foreach ($methods as $method) {
      $methods_[strval($method->getVisibility())] .= '<a href="#method:'
        . $method->getName() . '">' . $method->getName() . '</a><br>';
    }
    $this->html .= $this->templatesEngine->render('summary', [
      'methods' => $methods_,
      'properties' => $properties_,
      'constants' => $constants_,
    ]);
  }

  /**
   * @param \phpDocumentor\Reflection\Php\Constant[] $constants
   */
  private function writeConstants(array $constants): void {
    if (!empty($constants)) {
      $this->html .= $this->templatesEngine->render('constants',
        ['constants' => $constants]);
    }
  }

  /**
   * @param \phpDocumentor\Reflection\Php\Property[] $properties
   */
  private function writeProperties(array $properties): void {
    if (!empty($properties)) {
      $this->html .= $this->templatesEngine->render('properties',
        ['properties' => $properties]);
    }
  }

  /**
   * @param \phpDocumentor\Reflection\Php\Method[] $methods
   */
  private function writeMethods(array $methods): void {
    if (!empty($methods)) {
      $this->html .= $this->templatesEngine->render('methods',
        ['methods' => $methods]);
    }
  }

  /**
   * Generates PDF file.
   */
  private function create(): void {
    $this->WriteHTML($this->html);
    $this->output($this->path . '.pdf', 'F');
  }

  /**
   * @param \phpDocumentor\Reflection\Php\Interface_ $interface
   */
  public function createFromInterface(Interface_ $interface): void {
    $summary = $interface->getDocBlock() !== NULL ? $interface->getDocBlock()
      ->getSummary() : '';
    $description =
      $interface->getDocBlock() !== NULL ? $interface->getDocBlock()
        ->getDescription() : '';
    $this->writeHeader($interface->getName(), $summary, $description);
    $this->writeSummary($interface->getConstants(), [],
      $interface->getMethods());
    $this->writeConstants($interface->getConstants());
    $this->writeMethods($interface->getMethods());
    $this->create();
  }

  /**
   * @param \phpDocumentor\Reflection\Php\Trait_ $trait
   */
  public function createFromTrait(Trait_ $trait): void {
    $summary =
      $trait->getDocBlock() !== NULL ? $trait->getDocBlock()->getSummary() : '';
    $description =
      $trait->getDocBlock() !== NULL ? $trait->getDocBlock()->getDescription()
        : '';
    $this->writeHeader($trait->getName(), $summary, $description);
    $this->writeSummary([], $trait->getProperties(), $trait->getMethods());
    $this->writeProperties($trait->getProperties());
    $this->writeMethods($trait->getMethods());
    $this->create();
  }

}
