<?php namespace Dalmolin\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Exception;

class NginxAddSiteCommand extends BaseCommand 
{

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('add-site')
             ->setDescription('Add a new server to Nginx.')
             ->addArgument('server', InputArgument::REQUIRED)
             ->addArgument('dir', InputArgument::OPTIONAL);
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

        $server = $this->input->getArgument('server');
        $stub   = file_get_contents('https://gist.githubusercontent.com/luisdalmolin/72127805f91ef6090897/raw/562b177d1163f40ac0e7fd91546d241b4019dd76/site.stub');
        $stub   = str_replace('{{server}}', $server, $stub);
        $stub   = str_replace('{{dir}}', $this->getDir(), $stub);

        # creating the server file
        file_put_contents('/usr/local/etc/nginx/sites-available/' . $server, $stub);
        $this->info(' -> Virtual server ' . $server . ' created!');

        # symlinking the file
        $this->executeCommand('ln -s /usr/local/etc/nginx/sites-available/' . $server . ' /usr/local/etc/nginx/sites-enabled/' . $server);

        # restarting nginx
        $this->executeCommand('sudo launchctl unload /Library/LaunchDaemons/homebrew.mxcl.nginx.plist && sudo launchctl load /Library/LaunchDaemons/homebrew.mxcl.nginx.plist');
        $this->info(' -> Nginx restarted!');

        $this->info('');
        $this->comment(' -> Your virtual server was created!');
        $this->info('');
        $this->info(' -> Go for it! Go go! You can do this!');
    }

    protected function getDir()
    {
        if ($dir = $this->input->getArgument('dir')) {
            return str_replace('~', $_SERVER['HOME'], $this->input->getArgument('dir'));
        }

        return getcwd();
    }
}