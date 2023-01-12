<?php

namespace application;

use Symfony\Component\Yaml\Yaml;
use Phpactor\WorseReflection\ReflectorBuilder;
use Phpactor\WorseReflection\Bridge\Phpactor\MemberProvider\DocblockMemberProvider;
use phpDocumentor\Reflection\DocBlockFactory;

/**
 * Class FilesTreatment
 *
 * @package application
 */
class FilesTreatment {

  /**
   * Search for files by extension recursively.
   *
   * @param array $extensions
   *   Extensions to scan for.
   *
   * @return array
   *   Found files.
   */
  public function findFilesRecursive($path, array $extensions = []) {
    $directory = new \RecursiveDirectoryIterator($path);
    $iterator = new \RecursiveIteratorIterator($directory);
    $files = [];

    #$directory->setFlags(NULL);
    foreach ($extensions as $extension) {
      $regex = new \RegexIterator($iterator, '/^.+\.' . $extension . '$/i', \RecursiveRegexIterator::GET_MATCH);
      foreach ($regex as $item) {
        $files[] = reset($item);
      }
    }

    return $files;
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
    $files = [];

    foreach ($extensions as $extension) {
      $files += glob($path . $extension);
    }

    return $files;
  }

  /**
   * Get data from yaml file by pappern.
   *
   * @param string $path
   *   Full path to file.
   *
   * @return array
   *   Found data.
   */
  public function getYaml($path) {
    if (file_exists($path)) {
      return Yaml::parse(file_get_contents($path));
    }

    return [];
  }

  public function getClassInfo($path) {

    // Reflect.
    $code = file_get_contents($path);
    if (empty($code)) {
      return FALSE;
    }
    $info = $this->getClassPaths($code);
    if (empty($info)) {
      return FALSE;
    }
    $reflector = ReflectorBuilder::create()
      ->addSource($code)
      ->addMemberProvider(new DocblockMemberProvider())
      ->build();
    $reflected = $reflector->reflectClass($info['full_path']);
    $factory = DocBlockFactory::createInstance();
    $docblock = $factory->create($reflected->docblock()->raw());

    // Basic info.
    $info['summary'] = $docblock->getSummary();
    $info['description'] = $docblock->getDescription()->render();

    return $info;
  }

  /**
   * Gets the class code paths from the class code.
   *
   * @param string $code
   *   Class code.
   *
   * @return array
   *   Name, namespace, and full path of the class.
   */
  public function getClassPaths($code) {
    $code_paths = [];

    // Remove comments.
    $code = preg_replace('/\/\*.*\*\//Us', '', $code);
    $code = preg_replace('/\/\/.*\n/Us', '', $code);

    preg_match('/class\s(.*)(?=\s|{)/U', $code, $matches);
    if (!empty($matches[1]) && $name = trim($matches[1])) {
      $code_paths['name'] = $name;
      $code_paths['namespace'] = '\\';
      preg_match('/namespace(.*);/U', $code, $namespace_matches);
      if (!empty($namespace_matches[1]) && $namespace = trim($namespace_matches[1])) {
        $code_paths['namespace'] = $namespace;
      }
      $code_paths['full_path'] = $code_paths['namespace'] . '\\' . $code_paths['name'];

      // Plugin handling.
      if (str_contains($namespace, 'Plugin')) {
        $type = explode('\\Plugin\\', $namespace)[1];
        $parts = explode('\\', $type);
        $code_paths['plugin_type'] = $parts[0] ?? NULL;
        $code_paths['plugin_subtype'] = $parts[1] ?? NULL;
      }
    }

    return $code_paths;
  }

}
