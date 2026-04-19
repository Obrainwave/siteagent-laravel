<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SiteAgent API Key
    |--------------------------------------------------------------------------
    |
    | This key identifies your site to the ZuqoLab central platform.
    |
    */
    'api_key' => env('SITE_AGENT_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | SiteAgent Secret
    |--------------------------------------------------------------------------
    |
    | Used to sign requests via HMAC-SHA256 to ensure authenticity.
    |
    */
    'secret' => env('SITE_AGENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | SiteAgent Enabled
    |--------------------------------------------------------------------------
    |
    | Globally enable or disable the enforcement agent.
    |
    */
    'enabled' => env('SITE_AGENT_ENABLED', true),
];
