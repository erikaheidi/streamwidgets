<?php

namespace App\Command\Web;

use StreamWidgets\Exception\TwitchApiException;
use StreamWidgets\Service\StorageProvider;
use StreamWidgets\Twitch\TwitchService;
use StreamWidgets\Twitch\TwitchWidget;

class EventsController extends TwitchWidget
{
    public function show(TwitchService $twitch, StorageProvider $cache, $user_id)
    {
        try {
            $response = $twitch->subscribeToEvents();
        } catch (TwitchApiException $e) {
            echo "An error occurred.";
            exit;
        }


        var_dump($response);
    }
}