<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Documentation de Mon API',
            ],

            'routes' => [
                'api' => 'ma-doc-api',
            ],
            'paths' => [
                'use_absolute_path' => true,
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'format_to_use_for_docs' => 'json',
                'annotations' => [
                    base_path('app'),
                ],
            ],
        ],
    ],
    'defaults' => [
        'routes' => [
            'docs' => 'docs',
            'oauth2_callback' => 'api/oauth2-callback',
            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],
            'group_options' => [],
        ],

        'paths' => [
        'docs' => storage_path('api-docs'),  // Le répertoire où Swagger génère les fichiers
        'docs_json' => 'api-docs.json',      // Le nom du fichier JSON généré
        'docs_yaml' => 'api-docs.yaml',      // Le nom du fichier YAML généré
        'annotations' => [
            base_path('public/docs/api-docs.yaml'), // Le chemin vers votre fichier YAML
        ],
        'base' => env('L5_SWAGGER_BASE_PATH', null),
        'views' => base_path('resources/views/vendor/l5-swagger'),
    ],


        'scanOptions' => [
            'open_api_spec_version' => '3.0.0',
        ],

        'securityDefinitions' => [
            'securitySchemes' => [
                'passport' => [
                    'type' => 'oauth2',
                    'description' => 'Laravel passport oauth2 security.',
                    'in' => 'header',
                    'scheme' => 'https',
                    'flows' => [
                        'password' => [
                            'tokenUrl' => config('app.url') . '/oauth/token',
                            'scopes' => []
                        ],
                    ],
                ],
            ],
            'security' => [
                ['passport' => []],
            ],
        ],

        'generate_always' => true,

        'ui' => [
            'display' => [
                'dark_mode' => true,
                'doc_expansion' => 'full',
                'filter' => true,
            ],

            'authorization' => [
                'persist_authorization' => true,
            ],
        ],

        'constants' => [
            'L5_SWAGGER_CONST_HOST' => 'http://my-api-host.com',
        ],
    ],
];