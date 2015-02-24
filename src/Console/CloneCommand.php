<?php namespace Dalmolin\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CloneCommand extends BaseCommand 
{

    /**
     * Teams
     */
    protected $teams = [
        'escape'  => 'escapecria',
        'morgan'  => 'morgan-bbb',
        'e-lucre' => 'elucre',
    ];

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('clone')
             ->setDescription('Add a new database.')
             ->addArgument('repo', InputArgument::REQUIRED)
             ->addOption('team', 't', InputOption::VALUE_OPTIONAL, $description = 'O time que o repositorio deve ser clonado', 'escapecria');
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

        $repo = $this->input->getArgument('repo');
        $team = $this->getTeam();

        $this->comment(' -> Clonando o repositório ' . $repo . '... Aguarde...');
        $this->executeCommand('git clone git@bitbucket.org:'.$team.'/'.$repo.'.git');
        $this->info(' -> Repositório clonado com sucesso!');
    }

    protected function getTeam()
    {
        $team = $this->input->getOption('team');

        return isset($this->teams[$team]) ? $this->teams[$team] : $team;
    }
}