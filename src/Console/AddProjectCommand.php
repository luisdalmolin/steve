<?php namespace Dalmolin\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddProjectCommand extends BaseCommand 
{

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('add:project')
             ->setDescription('Add a new sublime project.')
             ->addArgument('project', InputArgument::OPTIONAL);
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
        $project = $this->getProjectName();
        $stub    = file_get_contents("https://gist.githubusercontent.com/luisdalmolin/72127805f91ef6090897/raw/9729b90e0b90ef49c601dee2d11568e18712691e/sublime-project.stub");
        $stub    = str_replace('{{dir}}', getcwd(), $stub);
        $file    = '/Users/luisdalmolin/Projects/sublime/' . $project . '.sublime-project';
        
        file_put_contents($file, $stub);

        # abrindo o sublime
        $this->executeCommand('subl ' . $file);
    }

    protected function getProjectName()
    {
        if (! $project = $this->input->getArgument('project')) {
            $dir     = explode('/', getcwd());
            $project = $dir[count($dir) - 1];
        }

        return $project;
    }
}