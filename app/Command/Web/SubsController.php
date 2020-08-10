<?php

namespace App\Command\Web;

use StreamWidgets\TwitchWidget;
use StreamWidgets\StorageService;
use StreamWidgets\TwitchServiceProvider;
use Twig\Environment;

class SubsController extends TwitchWidget
{
    public function show(TwitchServiceProvider $twitch, StorageService $cache, $user_id)
    {
        $subs = $cache->get(StorageService::CACHED_SUBS);

        if ($subs === null) {
            $subs = $twitch->getLatestSubs($user_id);
            $cache->save(json_encode($subs), StorageService::CACHED_SUBS);
        } else {
            $subs = json_decode($subs, true);
        }

        if ($subs) {
            $username = $this->getApp()->config->twitch_user_login;
            $latest_subs = array_column(array_reverse($subs['data']), 'user_name');

            $has_self = array_search($username, $latest_subs);
            if ($has_self !== false) {
                //removes own user from subs list
                unset($latest_subs[$has_self]);
            }

            /** @var Environment $twig */
            $twig = $this->getApp()->twig;

            $limit = $this->getParam('limit') ?? 2;
            echo $twig->render('twitch/subs.html.twig', [
                'subs' => array_slice($latest_subs, 0, $limit)
            ]);

        } else {
            echo "An error occurred.";
        }
    }
}