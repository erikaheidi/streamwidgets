<?php

namespace App\Command\Web;

use StreamWidgets\Service\GamesProvider;
use StreamWidgets\Twitch\TwitchGameInterface;
use StreamWidgets\WebController;

class GameController extends WebController
{
    public function handle()
    {
        $game_name = $this->getParam('name');

        if (!$game_name) {
            $game_name = "capture";
        }

        /** @var GamesProvider $games */
        $games = $this->getApp()->games;
        $game = $games->getGame($game_name);
        $storage = $games->storage;

        if ($game instanceof TwitchGameInterface) {
            $game->load($this->getApp()->twig, $storage, $this->getApp()->config->twitch_user_login);
            $game->run($this->getParams());
        }
    }
}