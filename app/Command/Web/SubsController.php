<?php

namespace App\Command\Web;

use StreamWidgets\Exception\TwitchApiException;
use StreamWidgets\Twitch\TwitchWidget;
use StreamWidgets\Service\StorageProvider;
use StreamWidgets\Twitch\TwitchService;
use Twig\Environment;

class SubsController extends TwitchWidget
{
    public function show(TwitchService $twitch, StorageProvider $cache, $user_id)
    {
        $subs = $cache->get(StorageProvider::CACHED_SUBS . '-' . $user_id);

        if ($subs === null) {
            try {
                $subs = $twitch->getLatestSubs($user_id);
                $cache->save(json_encode($subs), StorageProvider::CACHED_SUBS . '-' . $user_id);
            } catch (TwitchApiException $e) {
                // gets cached content if available
                $subs = $cache->getCached(StorageProvider::CACHED_SUBS . '-' . $user_id);

                if (!$subs) {
                    echo "Please (re)authorize the application <a href='/auth'>in this link</a>.";
                    exit;
                }
            }
        }

        if ($subs) {
            $subs = json_decode($subs, true);

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