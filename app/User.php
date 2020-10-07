<?php

namespace App;

use StreamWidgets\Twitch\TwitchService;

class User
{
    public string $id;

    public string $login;

    public string $user_data_file;

    public string $token;

    public function __construct(string $username)
    {
        $this->user_data_file = __DIR__ . '/../.users/' . $username . '.json';

        if ($this->exists()) {
            $user_info = json_decode(file_get_contents($this->user_data_file), 1);

            $this->id = $user_info['twitch_user_id'];
            $this->login = $user_info['twitch_user_login'];
            $this->token = $user_info['twitch_access_token'];
        }
    }

    public function exists()
    {
        return is_file($this->user_data_file);
    }

    public function validateToken(TwitchService $provider)
    {
        $user_id = $provider->getCurrentUserId();

        if ($user_id == $this->id) {
            return true;
        }

        return false;
    }

    static function save($access_token, array $user_info)
    {
        $data = [];
        $data['twitch_user_login'] = $user_info['login'];
        $data['twitch_user_id'] = $user_info['user_id'];
        $data['twitch_access_token'] = $access_token;

        $user_data_file = __DIR__ . '/../.users/' . $user_info['login'] . '.json';

        file_put_contents($user_data_file, json_encode($data));
    }
}