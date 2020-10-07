<?php

return [
    // General App Settings
    'app_path' => __DIR__ . '/app/Command',
    'data_dir' => __DIR__ . '/var/data',
    'cache_minutes' => 5, //followers and subs info cache expires in X minutes (reduces api reqs)
    'notifications_file' => __DIR__ . '/var/logs/notifications.txt',
    'twig_templates_path' => __DIR__ . '/app/views',
    'app_url' => 'http://localhost:8080',

    // Twitch App Credentials
    'twitch_client_id' => 'TWITCH_APP_CLIENT_ID',
    'twitch_client_secret' => 'TWITCH_APP_CLIENT_SECRET',
    'twitch_redirect_url' => 'http://localhost:8080/auth',

    // Twitch Chatbot Credentials. Register a user and get the chatbot keys.
    'twitch_user' => 'TWITCH_CHATBOT_USERNAME',
    'twitch_oauth' => 'TWITCH_CHATBOT_OAUTH_KEY',
    'chatbot_autoload' => __DIR__ . '/chatbot',
    //fill in this array with the channels that the bot will join
    'chatbot_channels' => [ 'erikaheidi' ],

    //Bot info that can be accessed from commands
    'bot_info' => [
        'info' => "https://github.com/erikaheidi/streamwidgets",

        'social' => [
            'Twitter' => 'https://twitter.com/erikaheidi',
            'YouTube' => 'https://youtube.com/c/erikaheidi',
            'Instagram' => 'https://instagram.com/erikaheidi'
        ],

        //relevant tutorials that can be located with !link tutorial-keyword
        'featured_tutorials' => [
            'ansible' => 'https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-ansible-on-ubuntu-20-04-pt',
            'docker' => 'https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-compose-on-ubuntu-20-04-pt',
            'laravel' => 'https://www.digitalocean.com/community/tutorials/how-to-install-and-set-up-laravel-with-docker-compose-on-ubuntu-20-04-pt',
            'lemp' => 'https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-on-ubuntu-20-04-pt',
            'lamp' => 'https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-20-04-pt',
            'composer' => 'https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-20-04',
        ],
    ],


];