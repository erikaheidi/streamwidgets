<?php

namespace App\Command\Web;

use StreamWidgets\Exception\TwitchApiException;
use StreamWidgets\Twitch\TwitchWidget;
use StreamWidgets\Service\StorageProvider;
use StreamWidgets\Twitch\TwitchService;
use Twig\Environment;

class BitsController extends TwitchWidget
{
    public function show(TwitchService $twitch, StorageProvider $cache, $user_id)
    {
        $leaderboard = $cache->get(StorageProvider::CACHED_LEADERBOARD . '-' . $user_id);
        $limit = $this->getParam('limit') ?? 3;

        if ($leaderboard === null) {
            try {
                $leaderboard = $twitch->getBitsLeaderboard($limit, 'all');
                $cache->save(json_encode($leaderboard), StorageProvider::CACHED_LEADERBOARD . '-' . $user_id);
            } catch (TwitchApiException $e) {
                // gets cached content if available
                $leaderboard = $cache->getCached(StorageProvider::CACHED_LEADERBOARD . '-' . $user_id);

                if (!$leaderboard) {
                    echo "Please (re)authorize the application <a href='/auth'>in this link</a>.";
                    exit;
                }
            }
        }

        if ($leaderboard) {
            $leaderboard = json_decode($leaderboard, true);

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