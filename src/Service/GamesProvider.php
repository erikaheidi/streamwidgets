<?php

namespace StreamWidgets\Service;

use Minicli\App;
use Minicli\ServiceInterface;
use StreamWidgets\FileStorage;
use StreamWidgets\Twitch\TwitchGameInterface;

class GamesProvider implements ServiceInterface
{
    /** @var array  */
    protected $registered_games = [];

    /** @var TwigProvider */
    protected $twig;

    /** @var FileStorage */
    public $storage;

    public $games_dir;

    public $games_public_dir;

    public $twitch_channel;

    public function load(App $app)
    {
        $this->storage = new FileStorage($app->config->games_storage_dir ?? __DIR__ . '/../../var/games/logs', 240);
        $this->games_dir = $app->config->games_dir ?? __DIR__ . '/../../var/games';
        $this->games_public_dir = $app->config->games_public_dir ?? __DIR__ . '/../../web/images/games';
        $this->twitch_channel = $app->config->twitch_user_login;
    }

    public function registerGame($name, TwitchGameInterface $game)
    {
        $game->bootstrap($this);
        $this->registered_games[$name] = $game;
    }

    public function getGame($name)
    {
        return $this->registered_games[$name] ?? null;
    }
}