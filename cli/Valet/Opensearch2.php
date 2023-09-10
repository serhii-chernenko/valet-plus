<?php

namespace Valet;

class Opensearch2 extends AbstractSearchEngine
{
    const ENGINE = 'opensearch@2.5';
    const CONTAINER = 'valet-opensearch_2.5.0';
    const COMPOSE = __DIR__ . '/../engines/opensearch2/compose.yaml';
}
