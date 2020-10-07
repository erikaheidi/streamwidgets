<?php

namespace StreamWidgets\Twitch;

use App\User;
use Minicli\App;
use Minicli\ServiceInterface;
use Minicli\Curly\Client;
use StreamWidgets\Exception\TwitchApiException;

class TwitchService implements ServiceInterface
{
    protected string $client_id;

    protected string $access_token;

    protected Client $client;

    public User $user;

    const API_VALIDATE = 'https://id.twitch.tv/oauth2/validate';

    public function load(App $app)
    {
        $client_id = $app->config->twitch_client_id;

        if ($client_id == null) {
            throw new \Exception("Missing Twitch Credentials.");
        }

        $this->client_id = $client_id;
        $this->client = new Client();
    }

    public function setAccessToken(string $access_token)
    {
        $this->access_token = $access_token;
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

        throw new TwitchApiException($response['body']);
    }

    public function getLatestSubs($user_id)
    {
        $url = sprintf("https://api.twitch.tv/helix/subscriptions?broadcaster_id=%s", $user_id);

        $response = $this->client->get($url, $this->getHeaders($this->client_id, $this->access_token));

        if ($response['code'] == 200) {
            return json_decode($response['body'], true);
        }

        throw new TwitchApiException($response['body']);
    }

    public function getBitsLeaderboard($count = 2, $period = 'week')
    {
        $url = sprintf("https://api.twitch.tv/helix/bits/leaderboard?count=%s&period=%s", $count, $period);

        $response = $this->client->get($url, $this->getHeaders($this->client_id, $this->access_token));

        if ($response['code'] == 200) {
            return json_decode($response['body'], true);
        }

        throw new TwitchApiException($response['body']);
    }

    public function subscribeToEvents()
    {
        $url =  "https://api.twitch.tv/helix/webhooks/hub";

        $topic_url = sprintf(
            "https://api.twitch.tv/helix/users/follows?first=1&to_id=%s",
            $this->user->id
        );

        $params = [
            'hub.callback' => "http://" . $this->user->login . ".swidgets.live/events",
            'hub.mode' => 'subscribe',
            'hub.lease_seconds' => 864000,
            'hub.topic' => $topic_url,
        ];

        $headers = $this->getHeaders($this->client_id, $this->user->token);
        $headers[] = 'Content-Type: application/json';

        $response = $this->client->post(
            $url,
            $params,
            $headers
        );

        print_r($response);
        if ($response['code'] == 202) {
            return json_decode($response['body'], true);
        }

        throw new TwitchApiException($response['body']);
    }

    protected function getHeaders($client_id, $access_token)
    {
        return [
            "Client-ID: $client_id",
            "Authorization: Bearer $access_token"
        ];
    }
}