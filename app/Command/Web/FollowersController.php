<?php

namespace App\Command\Web;

use StreamWidgets\Exception\TwitchApiException;
use StreamWidgets\Twitch\TwitchWidget;
use StreamWidgets\Service\StorageProvider;
use StreamWidgets\Twitch\TwitchService;
use Twig\Environment;

class FollowersController extends TwitchWidget
{
    public function show(TwitchService $twitch, StorageProvider $cache, $user_id)
    {
        $followers = $cache->get(StorageProvider::CACHED_FOLLOWERS . '-' . $user_id);
        
        if ($followers === null) {
            try {
                $followers = $twitch->getLatestFollowers($user_id);
                $cache->save(json_encode($followers), StorageProvider::CACHED_FOLLOWERS . '-' . $user_id);
            } catch (TwitchApiException $e) {
                // gets cached content if available
                $followers = $cache->getCached(StorageProvider::CACHED_FOLLOWERS . '-' . $user_id);

                if (!$followers) {
                    echo "Please (re)authorize the application <a href='/auth'>in this link</a>.";
                    exit;
                }
            }
        }

        if ($followers) {
            $followers = json_decode($followers, true);

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