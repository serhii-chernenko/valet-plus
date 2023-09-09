<?php

namespace Valet;

class Opensearch2 extends AbstractSearchEngine
{
    const ENGINE = 'Opensearch2';
    const CONTAINER = 'valet-opensearch_2.5.0';
    const COMPOSE = __DIR__ . '/../engines/opensearch2/compose.yaml';
}
