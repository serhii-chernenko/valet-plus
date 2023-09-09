<?php
return [
    'backend' => [
        'frontName' => 'admin'
    ],
    'db' => [
        'connection' => [
            'indexer' => [
                'host' => '127.0.0.1',
                'dbname' => 'DBNAME',
                'username' => 'root',
                'password' => 'root',
                'active' => '1',
                'persistent' => null,
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;'
            ],
            'default' => [
                'host' => '127.0.0.1',
                'dbname' => 'DBNAME',
                'username' => 'root',
                'password' => 'root',
                'active' => '1',
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;'
            ]
        ],
        'table_prefix' => ''
    ],
    'crypt' => [
        'key' => 'JkeEumwvvQBCDxypLPBozvrpF2rFNhNL'
    ],
    'session' => [
        'save' => 'redis',
        'redis' => [
            'host' => '/tmp/redis.sock',
            'port' => '6379',
            'password' => '',
            'timeout' => '2.5',
            'persistent_identifier' => '',
            'database' => '0',
            'compression_threshold' => '2048',
            'compression_library' => 'gzip',
            'log_level' => '1',
            'max_concurrency' => '6',
            'break_after_frontend' => '5',
            'break_after_adminhtml' => '30',
            'first_lifetime' => '600',
            'bot_first_lifetime' => '60',
            'bot_lifetime' => '7200',
            'disable_locking' => '0',
            'min_lifetime' => '60',
            'max_lifetime' => '2592000'
        ]
    ],
    'cache' => [
        'frontend' => [
            'default' => [
                'backend' => 'Cm_Cache_Backend_Redis',
                'backend_options' => [
                    'server' => '/tmp/redis.sock',
                    'port' => '6379',
                    'database' => '2'
                ]
            ],
            'page_cache' => [
                'backend' => 'Cm_Cache_Backend_Redis',
                'backend_options' => [
                    'server' => '/tmp/redis.sock',
                    'port' => '6379',
                    'database' => '1',
                    'compress_data' => '0'
                ]
            ]
        ]
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default'
        ]
    ],
    'x-frame-options' => 'SAMEORIGIN',
    'MAGE_MODE' => 'developer',
    'cache_types' => [
        'config' => 1,
        'layout' => 0,
        'block_html' => 0,
        'collections' => 1,
        'reflection' => 1,
        'db_ddl' => 1,
        'eav' => 1,
        'full_page' => 0,
        'config_integration' => 1,
        'config_integration_api' => 1,
        'target_rule' => 1,
        'translate' => 1,
        'config_webservice' => 1,
        'compiled_config' => 0,
        'customer_notification' => 1
    ],
    'install' => [
        'date' => 'Wed, 19 Jul 2017 00:00:00 +0000'
    ],
    'queue' => [
        'amqp' => [
            'host' => '',
            'port' => '',
            'user' => '',
            'password' => '',
            'virtualhost' => '/',
            'ssl' => ''
        ]
    ],
    'indexer' => [
        'batch_size' => [
            'cataloginventory_stock' => [
                'simple' => 200
            ],
            'catalog_category_product' => 666,
            'catalogsearch_fulltext' => [
                'partial_reindex' => 100,
                'mysql_get' => 500,
                'elastic_save' => 500
            ],
            'catalog_product_price' => [
                'simple' => 200,
                'default' => 500,
                'configurable' => 666
            ],
            'catalogpermissions_category' => 999,
            'inventory' => [
                'simple' => 210,
                'default' => 510,
                'configurable' => 616
            ]
        ]
    ],
    'system' => [
        'default' => [
            'dev' => [
                'static' => [
                    'sign' => 0
                ],
                'js' => [
                    'minify_files' => 0,
                    'merge_files' => 0,
                    'enable_js_bundling' => 0
                ],
                'css' => [
                    'minify_files' => 0,
                    'merge_css_files' => 0
                ],
                'template' => [
                    'minify_html' => 0
                ]
            ],
            'payment' => [
                'braintree' => [
                    'environment' => 'sandbox',
                    'sandbox_merchant_id' => '',
                    'sandbox_private_key' => '',
                    'sandbox_public_key' => '',
                    'active' => 1
                ],
                'braintree_cc_vault' => [
                    'active' => 1
                ],
                'checkmo' => [
                    'active' => 1
                ]
            ],
            'algoliasearch_credentials' => [
                'credentials' => [
                    'index_prefix' => 'magento2_loc_'
                ]
            ],
            'admin' => [
                'security' => [
                    'password_lifetime' => 0,
                    'password_is_forced' => 0,
                    'session_lifetime' => '31536000'
                ],
                'captcha' => [
                    'enable' => 0
                ]
            ],
            'web' => [
                'cookie' => [
                    'cookie_domain' => '',
                    'cookie_lifetime' => '999999'
                ],
                'unsecure' => [
                    'base_url' => 'URL'
                ],
                'secure' => [
                    'base_url' => 'URL',
                    'offloader_header' => 'X-Forwarded-Proto',
                    'use_in_frontend' => 1,
                    'use_in_adminhtml' => 1,
                ],
                'seo' => [
                    'use_rewrites' => 1
                ]
            ],
            'smile_elasticsuite_core_base_settings' => [
                'es_client' => [
                    'servers' => '127.0.0.1:SEARCH_PORT'
                ],
                'indices_settings' => [
                    'alias' => 'DBNAME'
                ]
            ],
            'catalog' => [
                'search' => [
                    'engine' => 'SEARCH_ENGINE',
                    'elasticsearch7_index_prefix' => 'DBNAME',
                    'elasticsearch7_server_hostname' => '127.0.0.1',
                    'elasticsearch7_server_port' => '9200',
                    'opensearch_index_prefix' => 'DBNAME',
                    'opensearch_server_hostname' => '127.0.0.1',
                    'opensearch_server_port' => '9300'
                ]
            ],
            'system' => [
                'full_page_cache' => [
                    'caching_application' => '1'
                ],
                'security' => [
                    'max_session_size_admin' => '2560000'
                ],
                'configuration' => [
                    'environment' => 'LOCAL'
                ]
            ],
        ]
    ],
    'modules' => [
        'Magento_TwoFactorAuth' => 0
    ]
];
