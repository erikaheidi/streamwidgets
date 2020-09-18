<?php

namespace StreamWidgets\Service;

use Minicli\App;
use Minicli\Minicache\FileCache;
use Minicli\ServiceInterface;

class GamesProvider implements ServiceInterface
{
    /** @var array  */
    protected $registered_games = [];

    /** @var TwigService */
    protected $twig;

    /** @var FileCache */
    public $storage;

    public $games_dir;

    public $games_public_dir;

    public function load(App $app)
    {
        $this->storage = new FileCache($app->config->games_storage_dir ?? __DIR__ . '/../var/games/logs');
        $this->games_dir = $app->config->games_dir ?? __DIR__ . '/../var/games';
        $this->games_public_dir = $app->config->games_public_dir ?? __DIR__ . '/../web/images/games';
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