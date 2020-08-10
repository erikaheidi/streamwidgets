<?php

namespace StreamWidgets;

use Minicli\Minicache\FileCache;

interface TwitchGameInterface
{
    public function bootstrap(GamesServiceProvider $games);

    public function load(...$params);

    public function run(array $params = []);

    public function show(array $params = []);
}