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

    public function checkDocker()
    {
        $docker = $this->cli->run('docker');

        if (preg_match('/command not found|Cannot connect/', $docker)) {
            throw new DomainException('Docker is not installed or run on this machine.');
        }

        $dockerCompose = $this->cli->run('docker compose');

        if (preg_match('/is not a docker command/', $dockerCompose)) {
            throw new DomainException('docker compose is not installed on this machine.');
        }
    }

    public function getContainer()
    {
        return $this->cli->run('docker ps | grep ' . static::CONTAINER);
    }

    public function start($showInfo = true)
    {
        $this->checkDocker();
        $container = $this->getContainer();

        if ($container && !$showInfo) {
            throw new DomainException('[' . static::ENGINE . '] is already running.');
        }

        if ($showInfo) {
            info('[' . static::ENGINE . '] Starting');
        }

        $this->cli->run('docker compose -f ' . static::COMPOSE . ' up -d --build --remove-orphans');
    }

    public function stop($showInfo = true)
    {
        $this->checkDocker();
        $container = $this->getContainer();

        if (!$container && !$showInfo) {
            throw new DomainException('[' . static::ENGINE . '] is already stopped.');
        }

        if ($showInfo) {
            info('[' . static::ENGINE . '] Stopping');
        }

        $this->cli->run('docker compose -f ' . static::COMPOSE . ' down');
    }

    public function restart()
    {
        info('[' . static::ENGINE . '] Restarting');
        $this->stop(false);
        $this->start(false);
    }

    public function logs()
    {
        $this->checkDocker();
        $logs = $this->cli->run('docker logs ' . static::CONTAINER);
        print_r($logs);
    }
}
