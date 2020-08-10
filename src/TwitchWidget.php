<?php

namespace StreamWidgets;

abstract class TwitchWidget extends WebController
{
    public function handle()
    {
        try {
            /** @var TwitchServiceProvider $twitch */
            $twitch = $this->getApp()->twitch;
        } catch (\Exception $e) {
            echo "Authentication Problem.";
            exit;
        }

        /** @var StorageService $cache */
        $cache = $this->getApp()->storage;
        $user_id = $cache->get(StorageService::CACHED_USERID);

        if ($user_id === null) {
            $user_id = $twitch->getCurrentUserId();
            $cache->save($user_id, StorageService::CACHED_USERID);
        }

        if (!$user_id) {
            echo "Credentials Problem.";
            exit;
        }

        $this->show($twitch, $cache, $user_id);
    }

    abstract public function show(TwitchServiceProvider $twitch, StorageService $cache, $user_id);
}