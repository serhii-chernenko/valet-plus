<p align="center"><img width="200" src="images/logo.png"></p>

## Introduction

Go here for the [valet+ documentation](https://github.com/weprovide/valet-plus/wiki).

## About the current fork

I'm [Serhii Chernenko](https://github.com/serhii-chernenko), and I'm a Magento 2 developer. 

I'm using Valet+ for my local development. I've created this fork to add some features that I need for my work.

## Fork features

- My fork requires [Docker](https://www.docker.com/products/docker-desktop/).
- Brew doesn't support Elasticsearch anymore. That's why I've added docker files for Elasticsearch.
- Since the upgraded Valet+ contains docker files, I decided to add docker files for Opensearch 1.2.0 and 2.5.0. The latest versions of Magento 2 require Opensearch.
- I've updated nginx.conf configs for Magento 2.
- I've upgraded app/etc/env.php file for `valet configure` command. Now it contains a lot of useful configs for Magento 2. But now the command couldn't be used without the specified search engine such as Elasticsearch or Opensearch, .e.g `valet configure elasticsearch7`. Available options are `elasticsearch7`, `opensearch`, `opensearch2`.
- `my.cnf` file fixed for MariaDB 10.4 version regarding the [slow reindex issue](https://magento.stackexchange.com/questions/336813/magento-2-reindex-very-slow-in-local-server/336816#336816).
- `env.php` file contains [batching configuration](https://developer.adobe.com/commerce/php/development/components/indexing/optimization/#batching-configuration) fixed for MariaDB 10.4 version regarding the [slow reindex issue](https://magento.stackexchange.com/questions/336813/magento-2-reindex-very-slow-in-local-server/336816#336816) as well.
- Memory limit and max execution time increased PHP configs.
- `http2` directive upgraded for nginx configs.
