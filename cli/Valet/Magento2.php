<?php

namespace Valet;

use Valet\CommandLine;
use Valet\Opensearch;
use Valet\Elasticsearch;
use Valet\Filesystem;
use Valet\Mysql;
use Valet\Configuration;
use Valet\Site;
use Valet\DevTools;
use ValetDriver;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class Magento2
{
    private $app;
    private $cli;
    private $opensearch;
    private $elasticsearch;
    private $files;
    private $mysql;
    private $config;
    private $site;
    private $devTools;

    public function __construct(
        Application   $app,
        CommandLine   $cli,
        Opensearch    $opensearch,
        Elasticsearch $elasticsearch,
        Filesystem    $files,
        Mysql         $mysql,
        Configuration $config,
        Site          $site,
        DevTools      $devTools
    ) {
        $this->app = $app;
        $this->cli = $cli;
        $this->opensearch = $opensearch;
        $this->elasticsearch = $elasticsearch;
        $this->files = $files;
        $this->mysql = $mysql;
        $this->config = $config;
        $this->site = $site;
        $this->devTools = $devTools;
    }

    public function install($input, $output, $edition, $version)
    {
        $lts = '2.4.6-p3';

        if (!$version) {
            $version = $lts;
        }

        $versions = [
            '2.4.7-beta2',
            '2.4.7-beta1',
            $lts,
            '2.4.6-p2',
            '2.4.6-p1',
            '2.4.6',
            '2.4.5-p5',
            '2.4.5-p4',
            '2.4.5-p3',
            '2.4.5-p2',
            '2.4.5-p1',
            '2.4.5',
            '2.4.4-p6',
            '2.4.4-p5',
            '2.4.4-p4',
            '2.4.4-p3',
            '2.4.4-p2',
            '2.4.4-p1',
            '2.4.4',
        ];

        if (!in_array($version, $versions)) {
            throw new Exception('Version not found. Available versions: ' . implode(', ', $versions));
        }

        $ce = 'community';
        $ee = 'enterprise';
        if (!$edition) {
            $edition = $ce;
        }
        $editions = [
            $ce => $ce,
            'ce' => $ce,
            'open' => $ce,
            'os' => $ce,
            $ee => $ee,
            'ee' => $ee,
            'commerce' => $ee
        ];

        if (!isset($editions[$edition])) {
            throw new Exception('Edition not found. Available editions: ' . $ce . ', ' . $ee);
        }

        info('Checking requirements');
        $this->checkRequirements();

        info(PHP_EOL . 'Installing Magento 2');
        output('- Version: ' . $version);
        output('- Edition: ' . $edition);
        output('- Directory: ' . getcwd());

        $helper = $this->app->getHelperSet()->get('question');
        $question = new ConfirmationQuestion(PHP_EOL . 'Continue? [Y/n] ', true);

        if (!$helper->ask($input, $output, $question)) {
            warning('Aborted');
            return;
        }

        $projectName = basename(getcwd());
        $searchEngine = preg_match('/\d\.\d\.[4,5]/', $version) ? 'elasticsearch7' : 'opensearch';

        $this->runCreateProject($edition, $version);
        $this->createDb($input, $output, $projectName);
        $this->fixFilesOwner();
        $this->runM2Installation($projectName, $searchEngine);
        $this->link();
        $this->app->runCommand('open ' . $projectName);
    }

    private function fixFilesOwner()
    {
        $this->cli->run('chown -R ' . user() . ' ' . getcwd());
    }

    private function checkRequirements()
    {

        $this->checkComposer();
        $this->checkAuthJson();
        $this->enableSearchEngines();
    }

    private function checkComposer()
    {
        output('- Checking Composer');

        $composer = $this->cli->runAsUser('which composer');

        if (!$composer) {
            throw new Exception('Composer not found');
        }

        $cmd = 'composer -V';
        info('- Composer found');
        output('- Checking Composer version (has to be V2). Running command:');
        output('- ' . $cmd);

        $version = $this->cli->runAsUser($cmd);
        $isVersionOld = preg_match('/1\.\d+\.\d+/', $version);

        if ($isVersionOld) {
            warning('- ' . trim($version));
            output('- Upgrading Composer to V2');
            $this->cli->quietlyAsUser('composer self-update --2');
        }

        info('- ' . trim($this->cli->runAsUser($cmd)));
    }

    private function checkAuthJson()
    {
        $fileName = 'auth.json';
        output('- Checking ' . $fileName . ' file');
        $localFilePath = getcwd() . '/' . $fileName;
        $isLocalExisting = $this->files->exists($this->files->realpath($localFilePath));

        if ($isLocalExisting) {
            return $this->checkAuthJsonContent($localFilePath);
        } else {
            warning('- ' . $localFilePath . ' not found');
        }

        $globalFilePath = '/Users/' . user() . '/.composer/' . $fileName;
        $isGlobalExisting = $this->files->exists($this->files->realpath($globalFilePath));

        if ($isGlobalExisting) {
            return $this->checkAuthJsonContent($globalFilePath);
        }

        throw new Exception(
            $globalFilePath . ' not found!' .
            PHP_EOL . PHP_EOL . '1. Generate it!' .
            PHP_EOL . $fileName . ' example:' .
            PHP_EOL . '- https://developer.adobe.com/commerce/contributor/guides/install/clone-repository/#authentication-file' .
            PHP_EOL . '- https://github.com/magento/magento2/blob/2.4-develop/auth.json.sample' .
            PHP_EOL . PHP_EOL . '2. Put the Magento Marketplace credentials to the ' . $fileName . ' file:' .
            PHP_EOL . 'https://commercemarketplace.adobe.com/customer/accessKeys/' .
            PHP_EOL . '- Public Key is username.' .
            PHP_EOL . '- Private Key is password.' .
            PHP_EOL . 'If you don\'t have keys there, generate them. Press the button "Create A New Access Key".'
        );
    }

    private function checkAuthJsonContent($file)
    {
        $file = $this->files->realpath($file);

        info('- ' . $file . ' found');
        output('- Checking auth.json content');

        $content = $this->files->get($file);
        $magentoRepo = 'repo.magento.com';

        if (strpos($content, $magentoRepo) === false) {
            throw new Exception($magentoRepo . ' not found in ' . $file);
        }

        info('- ' . $magentoRepo . ' found in ' . $file);
    }

    private function enableSearchEngines()
    {
        $opensearch = $this->opensearch->getContainer();
        $elasticsearch = $this->elasticsearch->getContainer();

        output('- Checking search engines');

        if ($opensearch && $elasticsearch) {
            info('- Opensearch and Elasticsearch are enabled');
        }

        if (!$opensearch) {
            $this->opensearch->start();
        }

        if (!$elasticsearch) {
            $this->elasticsearch->start();
        }
    }

    private function runCreateProject($edition, $version)
    {
        $vendor = $this->files->exists($this->files->realpath(getcwd() . '/vendor'));

        if ($vendor) {
            throw new Exception('The current directory is not empty.');
        }

        $cmd = 'composer create-project --repository-url=https://repo.magento.com/ magento/project-' . $edition . '-edition . ' . $version;
        info(PHP_EOL . 'Running command:');
        output($cmd);
        $this->cli->run($cmd);

        $binary = $this->files->exists($this->files->realpath(getcwd() . '/bin/magento'));

        if (!$binary) {
            throw new Exception(
                'Magento 2 installation failed.' .
                PHP_EOL . 'Try to re-run this command manually:' .
                PHP_EOL . $cmd
            );
        }
    }

    private function createDb($input, $output, $projectName)
    {
        $cmd = 'valet db create ' . $projectName;
        info(PHP_EOL . 'Running command:');
        output($cmd);

        $isDbExisting = $this->mysql->isDatabaseExists($projectName);
        if ($isDbExisting) {
            warning('Database "' . $projectName . '" already exists.');
            warning('If the existing database "' . $projectName . '" is important, please backup it before continuing.');
            warning('Run the command manually: ' . PHP_EOL . 'valet db export ' . $projectName);

            $helper = $this->app->getHelperSet()->get('question');
            $question = new ConfirmationQuestion(
                PHP_EOL . 'Do you want to drop the "' . $projectName . '" database? [Y/n] ',
                true
            );

            if (!$helper->ask($input, $output, $question)) {
                warning('Aborted');

                return;
            }

            $dropped = $this->mysql->dropDatabase($projectName);

            if (!$dropped) {
                throw new Exception('Error dropping database');
            }
        }

        $projectName = $this->mysql->createDatabase($projectName);

        if (!$projectName) {
            throw new Exception('Error creating database');
        }

        info('Database "' . $projectName . '"' . ($isDbExisting ? ' re-' : ' ') . 'created successfully');
    }

    private function runM2Installation($projectName, $searchEngine)
    {
        $this->fixFilesOwner();

        $esOption = str_replace('7', '', $searchEngine);
        $domain = 'https://' . $projectName . '.' . $this->config->read()['domain'];

        $cmd = 'bin/magento setup:install \
            --base-url=' . $domain . ' \
            --db-host=localhost \
            --db-name=' . $projectName . ' \
            --db-user=root \
            --db-password=' . Mysql::MYSQL_ROOT_PASSWORD . ' \
            --admin-firstname=Admin \
            --admin-lastname=Admin \
            --admin-email=admin@admin.com \
            --admin-user=admin \
            --admin-password=password123 \
            --backend-frontname=admin \
            --language=en_US \
            --currency=USD \
            --timezone=Europe/Kyiv \
            --use-rewrites=1 \
            --search-engine=' . $searchEngine . ' \
            --' . $esOption . '-host=localhost \
            --' . $esOption . '-port=' . ($searchEngine === 'opensearch' ? '9300' : '9200') . ' \
            --' . $esOption . '-index-prefix=' . $projectName . ' \
            --' . $esOption . '-timeout=15';
        info(PHP_EOL . 'Running command:');
        output($cmd);
        $this->cli->quietlyAsUser($cmd);
        $this->fixFilesOwner();

        $cmd = 'bin/magento setup:upgrade';
        info(PHP_EOL . 'Running command:');
        output($cmd);
        $this->cli->quietlyAsUser($cmd);

        $cmd = 'bin/magento deploy:mode:set developer';
        info(PHP_EOL . 'Running command:');
        output($cmd);
        $this->cli->quietlyAsUser($cmd);

        $fs = new SymfonyFilesystem();
        try {
            $fs->remove($this->files->realpath(getcwd() . '/app/etc/env.php'));
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while removing your file at " . $exception->getPath();
        }

        $m2Driver = ValetDriver::assign(getcwd(), $projectName, '/');

        if (method_exists($m2Driver, 'configure')) {
            $m2Driver->configure($this->devTools, $domain, $searchEngine);
        }
    }

    private function link()
    {
        $cmd = 'link --secure';
        info(PHP_EOL . 'Running command:');
        output('valet ' . $cmd);
        $this->app->runCommand($cmd);
    }

    public function uninstall($input, $output)
    {
        $path = getcwd();

        info('Uninstalling Magento 2');
        output('- Directory: ' . $path);

        $helper = $this->app->getHelperSet()->get('question');
        $question = new ConfirmationQuestion(PHP_EOL . 'Continue? [Y/n] ', true);

        if (!$helper->ask($input, $output, $question)) {
            warning('Aborted');
            return;
        }

        if (!$this->isMagento2Dir($path)) {
            throw new Exception('The current directory is not a Magento 2 project.');
        }

        $projectName = basename($path);

        $this->unsecure();
        $this->unlink();
        $this->dropDb($input, $output, $projectName);
        $this->deleteFiles($input, $output, $path);

        info(PHP_EOL . 'Magento 2 uninstalled successfully');
    }

    private function isMagento2Dir($path)
    {
        info(PHP_EOL . 'Checking if the current directory is a Magento 2 project');
        return $this->files->exists($this->files->realpath($path . '/bin/magento'));
    }

    private function unsecure()
    {
        $cmd = 'unsecure';
        info(PHP_EOL . 'Running command:');
        output('valet ' . $cmd);
        $this->app->runCommand($cmd);
    }

    private function unlink()
    {
        $cmd = 'unlink';
        info(PHP_EOL . 'Running command:');
        output('valet ' . $cmd);
        $this->app->runCommand($cmd);
    }

    private function dropDb($input, $output, $projectName)
    {
        info(PHP_EOL . 'Dropping database');
        $isDbExisting = $this->mysql->isDatabaseExists($projectName);

        if (!$isDbExisting) {
            warning('Database "' . $projectName . '" not found.');
            return;
        }

        $helper = $this->app->getHelperSet()->get('question');
        $question = new ConfirmationQuestion(
            PHP_EOL . 'Do you want to drop the "' . $projectName . '" database? [Y/n] ',
            true
        );

        if (!$helper->ask($input, $output, $question)) {
            warning('Aborted');

            return;
        }


        $cmd = 'valet db drop ' . $projectName;
        info(PHP_EOL . 'Running command:');
        output($cmd);
        $dropped = $this->mysql->dropDatabase($projectName);

        if (!$dropped) {
            throw new Exception('Error dropping "' . $projectName . '" database');
        }

        info('Database "' . $projectName . 'dropped successfully');
    }

    private function deleteFiles($input, $output, $path)
    {
        $helper = $this->app->getHelperSet()->get('question');
        $question = new ConfirmationQuestion(
            PHP_EOL . 'Do you want to delete the directory:' .
            PHP_EOL . $path .
            PHP_EOL . '? [Y/n] ',
            true
        );

        if (!$helper->ask($input, $output, $question)) {
            warning('Aborted');

            return;
        }

        $fs = new SymfonyFilesystem();
        $cmd = 'rm -rf ' . $path;
        info(PHP_EOL . 'Running command:');
        output($cmd);

        try {
            $fs->remove($this->files->realpath($path));
            info(PHP_EOL . 'Directory ' . $path . ' deleted successfully');
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while removing your directory at " . $exception->getPath();
        }
    }
}
