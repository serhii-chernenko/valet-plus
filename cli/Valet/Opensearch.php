<?php

namespace Valet;

class Opensearch extends AbstractSearchEngine
{
    const ENGINE = 'opensearch@1.2';
    const CONTAINER = 'valet-opensearch_1.2.0';
    const COMPOSE = __DIR__ . '/../engines/opensearch/compose.yaml';
}
