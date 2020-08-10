<?php

namespace App\Command\Web;

use StreamWidgets\TwitchWidget;
use StreamWidgets\StorageService;
use StreamWidgets\TwitchServiceProvider;
use Twig\Environment;

class FollowersController extends TwitchWidget
{
    public function show(TwitchServiceProvider $twitch, StorageService $cache, $user_id)
    {
        $followers = $cache->get(StorageService::CACHED_FOLLOWERS);

        if ($followers === null) {
            $followers = $twitch->getLatestFollowers($user_id);
            $cache->save(json_encode($followers), StorageService::CACHED_FOLLOWERS);
        } else {
            $followers = json_decode($followers, true);
        }

        if ($followers) {
            $limit = $this->getParam('limit') ?? 2;

            /** @var Environment $twig */
            $twig = $this->getApp()->twig;

            echo $twig->render('twitch/followers.html.twig', [
                'followers' => array_slice($followers['data'], 0, $limit)
            ]);

        } else {
            echo "An error occurred.";
        }
    }
}