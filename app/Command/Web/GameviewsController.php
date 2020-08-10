<?php

namespace App\Command\Web;

use StreamWidgets\GamesServiceProvider;
use StreamWidgets\TwitchGameInterface;
use StreamWidgets\WebController;

class GameviewsController extends WebController
{
    public function handle()
    {
        $game = $this->getParam('game');

        if (!$game) {
            $game = "capture";
        }

        /** @var GamesServiceProvider $games */
        $games = $this->getApp()->games;
        $storage = $games->storage;

        /** @var TwitchGameInterface $newgame */
        $newgame = $games->getGame($game);
        $newgame->load($this->getApp()->twig, $storage);
        echo $newgame->show($this->getParams());
    }
}