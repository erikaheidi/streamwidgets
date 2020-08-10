<?php

namespace StreamWidgets;

use Minicli\App;
use Minicli\ServiceInterface;
use Minicli\Curly\Client;

class TwitchServiceProvider implements ServiceInterface
{
    protected $client_id;

    protected $access_token;

    protected $client;

    const API_VALIDATE = 'https://id.twitch.tv/oauth2/validate';

    public function load(App $app)
    {
        $this->client_id = $app->config->twitch_client_id;
        $this->access_token = $app->config->twitch_access_token;

        if ($this->client_id === null OR $this->access_token === null) {
            throw new \Exception("Missing Twitch Credentials.");
        }

        $this->client = new Client();
    }

    public function getCurrentUserId()
    {
        $response = $this->client->get(
            self::API_VALIDATE,
            $this->getHeaders($this->client_id, $this->access_token)
        );

        if ($response['code'] == 200) {
            $user_info = json_decode($response['body'], 1);
            return $user_info['user_id'] ?? null;
        }

        return null;
    }

    public function getLatestFollowers($user_id)
    {
        $url = sprintf("https://api.twitch.tv/helix/users/follows?to_id=%s", $user_id);

        $response = $this->client->get($url, $this->getHeaders($this->client_id, $this->access_token));

        if ($response['code'] == 200) {
            return json_decode($response['body'], true);
        }

        return null;
    }

    public function getLatestSubs($user_id)
    {
        $url = sprintf("https://api.twitch.tv/helix/subscriptions?broadcaster_id=%s", $user_id);

        $response = $this->client->get($url, $this->getHeaders($this->client_id, $this->access_token));

        if ($response['code'] == 200) {
            return json_decode($response['body'], true);
        }

        return null;
    }

    public function getBitsLeaderboard($count = 2, $period = 'week')
    {
        $url = sprintf("https://api.twitch.tv/helix/bits/leaderboard?count=%s&period=%s", $count, $period);

        $response = $this->client->get($url, $this->getHeaders($this->client_id, $this->access_token));

        if ($response['code'] == 200) {
            return json_decode($response['body'], true);
        }

        return null;
    }

    protected function getHeaders($client_id, $access_token)
    {
        return [
            "Client-ID: $client_id",
            "Authorization: Bearer $access_token"
        ];
    }
}