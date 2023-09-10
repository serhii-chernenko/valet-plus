<?php

namespace Valet;

class Elasticsearch extends AbstractSearchEngine
{
    const ENGINE = 'elasticsearch';
    const CONTAINER = 'valet-elasticsearch_7.17.12';
    const COMPOSE = __DIR__ . '/../engines/elasticsearch/compose.yaml';
}
