<?php

/**
 * @file
 * The main PHP file.
 */

require_once './vendor/autoload.php';
require_once './settings.php';


require '../../vendor/autoload.php';
require 'CreateDocsCommand.php';

use Symfony\Component\Console\Application;
use application\CreateDocsCommand;

$app = new Application('drudoc');
$app->add(new CreateDocsCommand());
try {
  $app->run();
} catch (\Exception $e) {
}
