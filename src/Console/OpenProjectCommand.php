<?php namespace Dalmolin\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OpenProjectCommand extends BaseCommand 
{

    /**
     * The projects directory
     * 
     * @var  string
     */
    protected $dir = '~/Projects/sublime';

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('open:project')
             ->setDescription('Open an existing sublime project.')
             ->addArgument('project', InputArgument::REQUIRED);
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

        # criando o arquivo
        $project = $this->input->getArgument('project');

        # abrindo o sublime
        $this->executeCommand('subl ' . $this->getDir() . '/' . $project . '.sublime-project');
    }

    protected function getDir()
    {
        return str_replace('~', $_SERVER['HOME'], $this->dir);
    }
}