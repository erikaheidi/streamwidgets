<?php

namespace App\Command\Web;

use StreamWidgets\Service\GamesProvider;
use StreamWidgets\Twitch\TwitchGameInterface;
use StreamWidgets\WebController;

class GameviewsController extends WebController
{
    public function handle()
    {
        $game = $this->getParam('game');

        if (!$game) {
            $game = "capture";
        }

        /** @var GamesProvider $games */
        $games = $this->getApp()->games;
        $storage = $games->storage;

        /** @var TwitchGameInterface $newgame */
        $newgame = $games->getGame($game);
        $newgame->load($this->getApp()->twig, $storage, $this->getApp()->config->twitch_user_login);
        echo $newgame->show($this->getParams());
    }
}