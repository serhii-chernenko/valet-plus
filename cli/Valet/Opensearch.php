<?php

namespace Valet;

class Opensearch2 extends AbstractSearchEngine
{
    const ENGINE = 'opensearch';
    const CONTAINER = 'valet-opensearch';
    const COMPOSE = __DIR__ . '/../engines/opensearch/compose.yaml';
}
