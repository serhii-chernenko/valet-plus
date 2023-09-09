<?php

namespace Valet;

use DomainException;

class AbstractSearchEngine
{
    const ENGINE = '';
    const CONTAINER = '';
    const COMPOSE = '';

    /**
     * @var CommandLine
     */
    public $cli;

    /**
     * Elasticsearch constructor.
     * @param CommandLine $cli
     */
    public function __construct(
        CommandLine $cli
    ) {
        $this->cli = $cli;
    }

    private function checkDocker()
    {
        $docker = $this->cli->run('docker');

        if (preg_match('/command not found/', $docker)) {
            throw new DomainException('Docker is not installed or run on this machine.');
        }

        $dockerCompose = $this->cli->run('docker compose');

        if (preg_match('/is not a docker command/', $dockerCompose)) {
            throw new DomainException('docker compose is not installed on this machine.');
        }
    }

    private function getContainer()
    {
        return $this->cli->run('docker ps | grep ' . static::CONTAINER);
    }

    public function start()
    {
        $this->checkDocker();
        $container = $this->getContainer();

        if ($container) {
            throw new DomainException(static::ENGINE . ' is already running.');
        }

        $this->cli->run('docker compose -f ' . static::COMPOSE . ' up -d --build --remove-orphans');

        info(static::ENGINE . ' started.');
    }

    public function stop()
    {
        $this->checkDocker();
        $container = $this->getContainer();

        if (!$container) {
            throw new DomainException(static::ENGINE . ' is already stopped.');
        }

        $this->cli->run('docker compose -f ' . static::COMPOSE . ' down');
        info(static::ENGINE . ' stopped.');
    }

    public function restart()
    {
        $this->stop();
        $this->start();
    }

    public function logs()
    {
        $this->checkDocker();
        $logs = $this->cli->run('docker logs ' . static::CONTAINER);
        print_r($logs);
    }
}
