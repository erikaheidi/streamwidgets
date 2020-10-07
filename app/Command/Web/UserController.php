<?php

namespace App\Command\Web;

use StreamWidgets\Service\StorageProvider;
use StreamWidgets\Twitch\TwitchService;
use StreamWidgets\WebController;

class UserController extends WebController
{
    public function handle()
    {
        $user = $this->getParam('user');

        echo "$user 's profile";
    }

    public function show(TwitchService $twitch, StorageProvider $cache, $user_id)
    {
        echo $twitch->user->login . " is using StreamWidgets.";
    }
}