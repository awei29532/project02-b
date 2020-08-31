<?php

return [
    'paths' => [
        /*
         * File name of the generated json documentation file
        */
        'docs_json' => pathinfo(__FILE__, PATHINFO_FILENAME) . '-docs.json',

        /*
         * File name of the generated YAML documentation file
        */
        'docs_yaml' => pathinfo(__FILE__, PATHINFO_FILENAME) . '-docs.yaml',

        /*
         * Absolute paths to directory containing the swagger annotations are stored.
        */
        'annotations' => [
            base_path('app/Http/Controllers/Menu'),
        ],

    ],
];
