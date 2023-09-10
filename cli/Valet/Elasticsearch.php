<?php

namespace Valet;

class Elasticsearch extends AbstractSearchEngine
{
    const ENGINE = 'elasticsearch';
    const CONTAINER = 'valet-elasticsearch';
    const COMPOSE = __DIR__ . '/../engines/elasticsearch/compose.yaml';
}
