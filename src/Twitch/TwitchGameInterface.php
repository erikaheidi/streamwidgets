<?php

namespace StreamWidgets\Twitch;

use StreamWidgets\Service\GamesProvider;

interface TwitchGameInterface
{
    public function bootstrap(GamesProvider $games);

    public function load(...$params);

    public function run(array $params = []);

    public function show(array $params = []);
}