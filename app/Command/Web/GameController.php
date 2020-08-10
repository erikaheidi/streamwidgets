<?php

namespace App\Command\Web;

use Minicli\Minicache\FileCache;
use StreamWidgets\GamesServiceProvider;
use StreamWidgets\TwitchGameInterface;
use StreamWidgets\WebController;

class GameController extends WebController
{
    public function handle()
    {
        $game_name = $this->getParam('name');

        if (!$game_name) {
            $game_name = "capture";
        }

        /** @var GamesServiceProvider $games */
        $games = $this->getApp()->games;
        $game = $games->getGame($game_name);
        $storage = $games->storage;

        if ($game instanceof TwitchGameInterface) {
            $game->load($this->getApp()->twig, $storage);
            $game->run($this->getParams());
        }
    }
}