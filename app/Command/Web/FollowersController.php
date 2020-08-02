<?php

namespace App\Command\Web;

use Minicli\Minicache\FileCache;
use StreamWidgets\StorageService;
use StreamWidgets\TwitchServiceProvider;
use StreamWidgets\WebController;
use Twig\Environment;

class FollowersController extends WebController
{
    public function handle()
    {
        try {
            /** @var TwitchServiceProvider $client */
            $twitch = $this->getApp()->twitch;
        } catch (\Exception $e) {
            echo "Authentication Problem.";
            exit;
        }

        /** @var FileCache $cache */
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

            echo $twig->render('widgets/followers.html.twig', [
                'followers' => array_slice($followers['data'], 0, $limit)
            ]);

        } else {
            echo "An error occurred.";
        }
    }
}