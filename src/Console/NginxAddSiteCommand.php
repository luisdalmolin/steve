<?php namespace Dalmolin\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NginxAddSiteCommand extends BaseCommand 
{

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('add:site')
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
        $stub   = file_get_contents('https://gist.githubusercontent.com/luisdalmolin/72127805f91ef6090897/raw/site.stub');
        $stub   = str_replace('{{server}}', $server, $stub);
        $stub   = str_replace('{{dir}}', $this->getDir(), $stub);

        # adding this to vhosts
        $this->executeCommand('sudo sh -c "echo \'127.0.0.1   '.$server.'\' >> /etc/hosts"');
        $this->info(' -> ' . $server . ' adicionado nos vhosts!');

        # creating the server file
        file_put_contents('/usr/local/etc/nginx/sites-available/' . $server, $stub);
        $this->info(' -> Virtual server ' . $server . ' created!');

        # symlinking the file
        $this->executeCommand('ln -s /usr/local/etc/nginx/sites-available/' . $server . ' /usr/local/etc/nginx/sites-enabled/' . $server);

        # restarting nginx
        $this->executeCommand('sudo launchctl unload /Library/LaunchDaemons/homebrew.mxcl.nginx.plist && sudo launchctl load /Library/LaunchDaemons/homebrew.mxcl.nginx.plist');
        $this->info(' -> Nginx restarted!');

        $this->info('');
        $this->comment(' -> Your virtual server was created at ' . $server);
        $this->info('');
        $this->info(' -> Go for it! Go go! You can do this!');

        $this->executeCommand('open "http://"'. $server);
    }

    protected function getDir()
    {
        if ($dir = $this->input->getArgument('dir')) {
            return str_replace('~', $_SERVER['HOME'], $this->input->getArgument('dir'));
        }

        return getcwd() . '/public';
    }
}