<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | This option controls the default CSS framework used for styling the
    | searchable select component. Supported values: "tailwind", "bootstrap"
    |
    */

    'theme' => env('SEARCHABLE_SELECT_THEME', 'bootstrap'),  /*
    |--------------------------------------------------------------------------
    | Bootstrap Version
    |--------------------------------------------------------------------------
    |
    | Specify the Bootstrap version you're using. This helps optimize
    | the CSS classes for your specific Bootstrap version.
    | Supported values: "5"
    |
    */

    'bootstrap_version' => env('SEARCHABLE_SELECT_BOOTSTRAP_VERSION', '5'),
];
