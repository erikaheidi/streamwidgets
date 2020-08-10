<?php

namespace App\Command\Web;

use StreamWidgets\TwitchWidget;
use StreamWidgets\StorageService;
use StreamWidgets\TwitchServiceProvider;
use Twig\Environment;

class BitsController extends TwitchWidget
{
    public function show(TwitchServiceProvider $twitch, StorageService $cache, $user_id)
    {
        $leaderboard = $cache->get(StorageService::CACHED_LEADERBOARD);
        $limit = $this->getParam('limit') ?? 3;

        if ($leaderboard === null) {
            $leaderboard = $twitch->getBitsLeaderboard($limit, 'all');
            $cache->save(json_encode($leaderboard), StorageService::CACHED_LEADERBOARD);
        } else {
            $leaderboard = json_decode($leaderboard, true);
        }

        if ($leaderboard) {
            /** @var Environment $twig */
            $twig = $this->getApp()->twig;

            echo $twig->render('twitch/topbits.html.twig', [
                'leaderboard' => array_slice($leaderboard['data'], 0, $limit)
            ]);

        } else {
            echo "An error occurred.";
        }
    }
}