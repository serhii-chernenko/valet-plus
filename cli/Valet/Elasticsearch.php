<?php

namespace Valet;

class Elasticsearch extends AbstractSearchEngine
{
    const ENGINE = 'Elasticsearch';
    const CONTAINER = 'valet-elasticsearch_7.17.5';
    const COMPOSE = __DIR__ . '/../engines/elasticsearch/compose.yaml';
}
