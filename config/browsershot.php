<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chrome Binary Path
    |--------------------------------------------------------------------------
    |
    | The path to the Chrome binary. If null, Browsershot will try to find
    | Chrome automatically. You can also set this to a specific path if needed.
    |
    */
    'chrome_path' => env('BROWSERSHOT_CHROME_PATH', null),

    /*
    |--------------------------------------------------------------------------
    | Puppeteer Cache Path
    |--------------------------------------------------------------------------
    |
    | The path to the Puppeteer cache directory where Chrome is installed.
    | This is used to automatically find Chrome when chrome_path is null.
    |
    */
    'puppeteer_cache_path' => env('BROWSERSHOT_PUPPETEER_CACHE_PATH', null),

    /*
    |--------------------------------------------------------------------------
    | Chrome Library Path
    |--------------------------------------------------------------------------
    |
    | Some headless Chrome builds depend on shared libraries that may not be
    | available globally. Point this to a directory that contains libasound
    | and other required libraries when running inside a constrained
    | environment.
    |
    */
    'library_path' => env('BROWSERSHOT_LIBRARY_PATH', null),

    /*
    |--------------------------------------------------------------------------
    | Default Chrome Arguments
    |--------------------------------------------------------------------------
    |
    | Default arguments to pass to Chrome when generating PDFs.
    |
    */
    'default_args' => [
        '--no-sandbox',
        '--disable-setuid-sandbox',
        '--disable-dev-shm-usage',
        '--disable-gpu',
        '--no-first-run',
        '--disable-background-timer-throttling',
        '--disable-backgrounding-occluded-windows',
        '--disable-renderer-backgrounding',
        '--disable-features=TranslateUI',
        '--disable-ipc-flooding-protection',
        '--disable-web-security',
        '--disable-features=VizDisplayCompositor',
    ],
];
