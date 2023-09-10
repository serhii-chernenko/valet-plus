# Valet+ for Magento 2 by Magento 2 Engineer
<hr/>

## Introduction

Go here for the [valet+ documentation](https://github.com/weprovide/valet-plus/wiki).

## About the current fork

I'm [Serhii Chernenko](https://github.com/serhii-chernenko), and I'm a Magento 2 developer.

I'm using Valet+ for my local development. I've created this fork to add some features that I need for my work.

## Fork features

- My fork requires [Docker](https://www.docker.com/products/docker-desktop/).
- Brew doesn't support Elasticsearch anymore. That's why I've added docker files for Elasticsearch.
- Since the upgraded Valet+ contains docker files, I decided to add docker files for Opensearch. The latest versions of Magento 2 (2.4.6+) require Opensearch.
- I've updated `nginx.conf` configs for Magento 2.
- I've upgraded `app/etc/env.php` file for `valet configure` command. Now it contains a lot of useful configs for Magento 2. But now the command couldn't be used without the specified search engine such as Elasticsearch or Opensearch, .e.g `valet configure elasticsearch`. Available options are `elasticsearch`, `opensearch`.
- `my.cnf` file fixed for MariaDB 10.4 version regarding the [slow reindex issue](https://magento.stackexchange.com/questions/336813/magento-2-reindex-very-slow-in-local-server/336816#336816).
- `env.php` file contains [batching configuration](https://developer.adobe.com/commerce/php/development/components/indexing/optimization/#batching-configuration) fixed for MariaDB 10.4 version regarding the [slow reindex issue](https://magento.stackexchange.com/questions/336813/magento-2-reindex-very-slow-in-local-server/336816#336816) as well.
- Memory limit and max execution time increased PHP configs.
- `http2` directive upgraded for nginx configs.

## Installation

### Install PHP to macOS via Homebrew

```shell
brew update
brew tap shivammathur/php
brew install php@8.1
brew link php@8.1 --force --overwrite
```

Make sure that PHP is working correctly

```shell
php -v
```

It has to return something like:

```
PHP 8.1.22 (cli) (built: Aug  3 2023 17:09:58) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.1.22, Copyright (c) Zend Technologies
    with Zend OPcache v8.1.22, Copyright (c), by Zend Technologies
```

### Install Composer

```shell
brew install composer
```

### Require the composer package globally

You can run this command from any directory and any time, 'cause I'll be updating the package sometimes:

```shell
composer global require serhii-chernenko/valet-plus:dev-feature/magento2-improvement
```

Make `composer` command executable globally:

```bash
export PATH="$PATH:$HOME/.composer/vendor/bin"
```

to `~/.zshrc` (for zsh) or `~/.bash_profile` (for bash). 

Depends on your shell:

```shell
echo $SHELL
```

Check the result:

```shell
composer -V
```

It has to return something like:

```
Composer version 2.5.8 2023-06-09 17:13:21
```

### Install Docker

Install [Docker Desktop](https://www.docker.com/products/docker-desktop/).

### Install Valet+ with MariabDB 10.4

Check for common issues preventing Valet+ from installing
```shell
valet fix
```

Install Valet+

```bash
# With Mariadb 10.4 (I recommend)
valet install --with-mariadb
# With Mysql 5.7
valet install
# With Mysql 8
valet install --with-mysql-8
```

## Magento 2 Installation

### Add Magento 2 files

Choose a directory where you have your projects:

```shell
cd ~/dev/main
```

### Create a database for the project:

```shell
valet db create m246
```

### Run search engines

Opensearch:

```shell
valet opensearch on
```

Elasticsearch:

```shell
valet elasticsearch on
```

### Get authentication keys from Magento Marketplace

Make sure that you have `auth.json` file globally in `~/.composer/` or in a project directory (that will be created next steps).

`auth.json` has to contain the keys:

```json
{
    "http-basic": {
        "repo.magento.com": {
            "username": "",
            "password": ""
        }
    }
}
```

If you don't have the file or it doesn't contain the keys, you can get them from the [Adobe Commerce Marketplace](https://commercemarketplace.adobe.com/customer/accessKeys/).

See more details in the [video](https://youtu.be/KWo3yMTeUEI?t=1357&si=iv9PfbpiEwLzXIA4).

When you will run the `composer create-project` or `composer install` command, you will be asked to enter the keys. So, don't worry if you don't have the `auth.json` file at this step.

#### Magento 2 New Project Installation

Create a new directory for the project.<br/>
Replace `m246` to any folder name you want:
    
```shell
mkdir m246
```
Go to the created folder:

```shell
cd $_
```

Require [Magento 2 via Composer](https://experienceleague.adobe.com/docs/commerce-operations/installation-guide/composer.html?lang=en#get-the-metapackage):

Community Edition:

```shell
composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition .
```

Commerce (Enterprise) Edition:

```shell
composer create-project --repository-url=https://repo.magento.com/ magento/project-enterprise-edition .
```

Run the [Magento 2 installation](https://experienceleague.adobe.com/docs/commerce-operations/installation-guide/composer.html?lang=en#install-the-application):

For 2.4.6+ version with Opensearch:

```shell
bin/magento setup:install \
    --base-url=https://m246.test \
    --db-host=localhost \
    --db-name=m246 \
    --db-user=root \
    --db-password=root \
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
    --search-engine=opensearch \
    --opensearch-host=localhost \
    --opensearch-port=9300 \
    --opensearch-index-prefix=m246 \
    --opensearch-timeout=15
```

For <= 2.4.5 version with Elasticsearch:

```shell
bin/magento setup:install \
    --base-url=https://m246.test \
    --db-host=localhost \
    --db-name=m246 \
    --db-user=root \
    --db-password=root \
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
    --search-engine=elasticsearch7 \
    --elasticsearch-host=localhost \
    --elasticsearch-port=9200 \
    --elasticsearch-index-prefix=m246 \
    --elasticsearch-timeout=15
```

#### Magento 2 Existing Project Installation

Clone the repo to a directory for the project.<br/>
Replace clone command to your repo.<br/>
Replace `m246` to any folder name you want or remove it the have the same name as the repo has:

```shell
git clone git@domain.com:vendor/project.git m246
```

Go to the cloned folder:

```shell
cd m246
```

Run the composer installation:

```shell
composer install
```

Import a database dump to the created database. 

```shell
valet db import dump.sql
```

It supports GZIP files. I recommend using them for big dumps:

```shell
valet db import dump.sql.gz
```

### Link the project directory to Valet+

```shell
valet link
```

It links a domain by the directory name by default, but you can specify a custom name:

```shell
valet link m246
```

### Secure the project with HTTPS

I recommend using HTTPS for all projects. And only secured Nginx config contains useful configs for Magento 2.

```shell
valet secure
```

### Generate `env.php` file

If you've installed a clean project, remove the generated `env.php` file. You need this, 'cause `valet configure` command will generate a new one with useful configs for Magento 2.

```shell
rm app/etc/env.php
```

Generate `env.php` file for Magento 2.4.6+ version with Opensearch:

```shell
valet configure opensearch
```

Generate `env.php` file for Magento 2.4.5 version with Elasticsearch:

```shell
valet configure elasticsearch
```

Check the generated file, it has to contain useful configs for Magento 2 in comparison with the default one:

- Disabled cache types such as `layout`, `block_html`, and `full_page`
- Batch size for indexing (for MariaDB 10.4)
- Search engine configs. It checks if you have installed Elasticsuite module
- Disabled merging and minification of JS and CSS files
- Disabled `Magento_TwoFactorAuth` module
- Increased cookie lifetime for admin and frontend
- Set Algolia local index

### Upgrade the application

```shell
bin/magento setup:upgrade
```

### Switch to the `developer` mode

```shell
bin/magento deploy:mode:set developer
```

### Open the project in your browser

https://m246.test

### Create Admin user

```shell
bin/magento admin:user:create
```

### Create a customer

```shell
magerun2 customer:create
```

### Install sample data

```shell
bin/magento sampledata:deploy
```

## Search Engines

### Domains

- Opensearch: http://localhost:9300
- Elasticsearch: http://localhost:9200

### Logs

Opensearch:

```shell
valet opensearch logs
```

Elasticsearch:

```shell
valet elasticsearch logs
```

## Mailhog (catch all emails)

- http://localhost:8025
- http://mailhog.test

## Xdebug

Enable:

```shell
valet xdebug on
```

Disable:

```shell
valet xdebug off
```

## Switch PHP version

8.1:

```shell
valet use php@8.1
```

7.4:

```shell
valet use php@7.4
```

## Upgrade the Valet+ tool

Sometimes I'll be updating the package.

Before the upgrading, stop the Valet+ service:

```shell
valet stop
```

You can upgrade it with the following command:

```shell
composer global require serhii-chernenko/valet-plus:dev-feature/magento2-improvement
```

Run fix command after the upgrade:

```shell
valet fix
```

## Get in touch

- [YouTube Channel](https://youtube.com/@serhii.chernenko)
- [Telegram Channel](https://t.me/serhii_chernenko)
- [Telegram Group](https://t.me/serhii_chernenko_chat)
- [LinkedIn](https://linkedin.com/in/serhii-chernenko)
- [Portfolio](https://chernenko.digital)
- [Magento 2 instance with workshops examples](https://m246.chernenko.work)
- Email: <a href="mailto:contact@chernenko.digital">contact@chernenko.digital</a>

## Support me

<table>
  <thead>
    <tr>
      <th><strong>Buy me a coffee (USD)</strong></th>
      <!--<th><strong>Monobank (USD)</strong></th>-->
      <th><strong>Monobank (UAH)</strong></th>
      <!--<th><strong>Monobank (EUR)</strong></th>-->
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <a href="https://www.buymeacoffee.com/serhiichernenko" target="_blank">
          <img src="https://user-images.githubusercontent.com/28815318/237051591-a196cad8-cdf8-4ec2-adc3-124336b62597.png" alt="QR code"/>
        </a>
      </td>
      <!--<td>
        <a href="https://send.monobank.ua/jar/5pKvSvTFYU" target="_blank">
          <img src="https://user-images.githubusercontent.com/28815318/237056378-7bf7a06a-7a74-4765-bc8d-f746ef0791fa.png" alt="QR code"/>
        </a>
      </td>-->
      <td>
        <a href="https://send.monobank.ua/jar/4ZGhPQqyMh" target="_blank">
          <img src="https://user-images.githubusercontent.com/28815318/237060525-09c502cf-6047-49d2-9d8b-4fed497f4aef.png" alt="QR code"/>
        </a>
      </td>
      <!--<td>
        <a href="https://send.monobank.ua/jar/7uSXV1eTpX" target="_blank">
          <img src="https://user-images.githubusercontent.com/28815318/237057402-6953dade-60d6-4a17-985d-97d4688c9724.png" alt="QR code"/>
        </a>
      </td>-->
    </tr>
    <tr>
      <td colspan="4" align="center"><strong>Click on a QR-code to open the link instead of scanning.</strong></td>
    </tr>
  </tbody>
</table>

## Free course about the Front-End of Magento 2 / Adobe Commerce
[![01  Вступ fixed](https://user-images.githubusercontent.com/28815318/230770894-119f79aa-7c93-4f18-9dbd-8fe5b060eb9f.png)](https://youtube.com/playlist?list=PLSep1ckXq6QGE1u23jafNnlT-2BOCKxVZ)
