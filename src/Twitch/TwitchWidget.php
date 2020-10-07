<?php

namespace StreamWidgets\Twitch;

use App\User;
use StreamWidgets\Service\StorageProvider;
use StreamWidgets\WebController;

abstract class TwitchWidget extends WebController
{
    public function handle()
    {
        $username = $this->getParam('user');
        $user = new User($username);

        if (!$user->exists()) {
            throw new \Exception('User not found.');
        }

        /** @var TwitchService $twitch */
        $twitch = $this->getApp()->twitch;
        $twitch->setAccessToken($user->token);

        if ($user->validateToken($twitch)) {
            //get updated things?
        }

        /** @var StorageProvider $cache */
        $cache = $this->getApp()->storage;
        $user_id = $user->id;

        if (!$user_id) {
            echo "User Validation Problem.";
            exit;
        }

        $this->show($twitch, $cache, $user_id);
    }

    abstract public function show(TwitchService $twitch, StorageProvider $cache, $user_id);
}