# Valet+ for Magento 2 by Magento 2 Engineer

Documentation: [Wiki](https://github.com/serhii-chernenko/valet-plus/wiki).
<hr/>

## Introduction

Go here for the [Valet+ original documentation](https://github.com/weprovide/valet-plus/wiki).

And here the [documentation of the current fork](https://github.com/serhii-chernenko/valet-plus/wiki).

## About the current fork

I'm [Serhii Chernenko](https://github.com/serhii-chernenko), and I'm a Magento 2 Lead Front-End Developer.

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
- `valet share` command fixed.
- Ngrok upgraded from 2 to 3 version.
- `m2 install` command. Install Magento 2 in one command!

## Magento 2 Installation

Follow the [documentation of the current fork](https://github.com/serhii-chernenko/valet-plus/wiki).

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
