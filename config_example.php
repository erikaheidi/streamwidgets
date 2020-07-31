<?php

return [
    // General App Settings
    'app_path' => __DIR__ . '/app/Command',

    // Twig Settings for Browser Widgets
    'twig_templates_path' => __DIR__ . '/app/views',
    'twig_cache_path' => __DIR__ . '/var/cache',

    // Twitch Credentials.
    // Register app at: https://dev.twitch.tv/console/apps and use http://localhost:8000/twitch as callback
    // Then, run the local server and access the /twitch endpoint to authorize your app and obtain your access token
    'twitch_user_login' => 'YOUR_TWITCH_USERNAME',
    'twitch_client_id' => 'YOUR_TWITCH_APP_CLIENT_ID',
    'twitch_access_token' => 'YOUR_PERSONAL_ACCESS_TOKEN',
    'twitch_redirect_url' => 'http://localhost:8000/twitch'
];