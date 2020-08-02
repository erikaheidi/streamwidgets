<?php

namespace App\Command\Web;

use Minicli\Curly\Client;
use Minicli\Minicache\FileCache;
use StreamWidgets\StorageService;
use StreamWidgets\TwitchServiceProvider;
use StreamWidgets\WebController;
use Twig\Environment;

class SubsController extends WebController
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

        $subs = $cache->get(StorageService::CACHED_SUBS);

        if ($subs === null) {
            $subs = $twitch->getLatestSubs($user_id);
            $cache->save(json_encode($subs), StorageService::CACHED_SUBS);
        } else {
            $subs = json_decode($subs, true);
        }

        if ($subs) {
            $limit = $this->getParam('limit') ?? 2;

            /** @var Environment $twig */
            $twig = $this->getApp()->twig;

            echo $twig->render('widgets/subs.html.twig', [
                'subs' => array_slice($subs['data'], 0, $limit)
            ]);

        } else {
            echo "An error occurred.";
        }
    }

    protected function getHeaders($client_id, $access_token)
    {
        return [
            "Client-ID: $client_id",
            "Authorization: Bearer $access_token"
        ];
    }
}