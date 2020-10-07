<?php

namespace App\Games;

use StreamWidgets\FileStorage;
use StreamWidgets\Service\GamesProvider;
use StreamWidgets\Service\TwigProvider;
use StreamWidgets\Twitch\TwitchGameInterface;

class Capture implements TwitchGameInterface
{
    /** @var FileStorage */
    protected $storage;

    protected $resources_dir;

    protected $public_dir;

    protected $channel;

    /** @var TwigProvider */
    protected $twig;

    const STORAGE_QUEUE = 'GAME_CAPTURE';
    const STORAGE_ALL = 'GAME_CAPTURE_LOG';
    const STORAGE_PARTY = 'GAME_CAPTURE_PARTY';

    public function bootstrap(GamesProvider $games)
    {
        $this->resources_dir = $games->games_dir . '/capture';
        $this->public_dir = $games->games_public_dir . '/capture';
    }

    public function load(...$params)
    {
        $this->twig = $params[0];
        $this->storage = $params[1];
        $this->channel = $params[2];
    }

    public function show(array $params = [])
    {
        if (isset($params['scene']) && $params['scene'] == 'lounge') {

            $party = json_decode($this->storage->getCachedUnlessExpired(self::STORAGE_PARTY . '-' . $this->channel), true);

            return $this->twig->render("games/capture_lounge.html.twig", array_merge($params, [
                'captures' => $party
            ]));
        }

        $captured = null;
        $queue = $this->storage->getCachedUnlessExpired(self::STORAGE_QUEUE . '-' . $this->channel);

        if ($queue) {
            $captures = array_reverse(json_decode($queue, true));
            $captured = array_pop($captures);

            //save queue back minus popped capture
            $this->save(json_encode($captures), self::STORAGE_QUEUE . '-' . $this->channel);
        }

        return $this->twig->render("games/capture.html.twig", array_merge($params, [
            'captured' => $captured
        ]));
    }

    public function save($content, $unique_key)
    {
        $this->storage->save($content, $unique_key);
    }

    public function run(array $params = [])
    {
        $phant = $params['phant'];
        $user = $params['user'];

        $phant_data = [
            'type' => $phant,
            'user' => $user,
            'capture_time' => date('Y-m-d H:i:s')
        ];

        $accumulated = json_decode($this->storage->getCachedUnlessExpired(self::STORAGE_ALL . '-' . $this->channel), true);
        $accumulated[] = $phant_data;

        $queue = json_decode($this->storage->getCachedUnlessExpired(self::STORAGE_QUEUE . '-' . $this->channel), true);
        $queue[] = $phant_data;

        $party = json_decode($this->storage->getCachedUnlessExpired(self::STORAGE_PARTY . '-' . $this->channel), true);
        $total = $party[$user]['total_captures'] ?? 0;

        $party[$user] = [
            'latest' => $phant,
            'total_captures' => $total + 1
        ];

        $this->save(json_encode($queue), self::STORAGE_QUEUE . '-' . $this->channel);
        $this->save(json_encode($accumulated), self::STORAGE_ALL . '-' . $this->channel);
        $this->save(json_encode($party), self::STORAGE_PARTY . '-' . $this->channel);
    }
}