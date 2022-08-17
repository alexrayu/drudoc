<?php
namespace application;
require 'Command.php';

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateDocsCommand
 * @package application
 */
class CreateDocsCommand extends Command {

    /**
     * Configure method.
     */
    public function configure(): void
    {
        $this->setName('create')
            ->setDescription('Create markdown and HTML format documentation.')
            ->setHelp('Generates documentation for a Drupal module.')
            ->addArgument('inputPath', InputArgument::REQUIRED, 'Source file or directory.')
            ->addArgument('outputPath', InputArgument::REQUIRED, 'Destination directory.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->createDocs($input, $output);
    }
}
