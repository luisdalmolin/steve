<?php namespace Dalmolin\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddDatabaseCommand extends BaseCommand 
{

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('add:database')
             ->setDescription('Add a new database.')
             ->addArgument('name', InputArgument::REQUIRED);
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setInputInterface($input);
        $this->setOutputInterface($output);

        $name = $this->input->getArgument('name');

        $this->executeCommand("echo 'create database `" . $name . "`' | mysql -u root");
        $this->info(' -> Database ' . $name . ' successfully created!');
    }
}