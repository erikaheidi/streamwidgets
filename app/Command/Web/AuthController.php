<?php

namespace App\Command\Web;

use App\User;
use Minicli\Curly\Client;
use StreamWidgets\WebController;

class AuthController extends WebController
{
    static $TWITCH_AUTH_URL = 'https://id.twitch.tv/oauth2/authorize';

    public function handle()
    {
        if (php_sapi_name() === 'cli') {
            $this->getPrinter()->error("This endpoint must be executed from a browser.");
            return;
        }

        if (!$this->getApp()->config->has('twitch_client_id')) {
            echo "Twitch Client ID not found. Check your config.php";
            exit;
        }

        $client_id = $this->getApp()->config->twitch_client_id;
        $client_secret = $this->getApp()->config->twitch_client_secret;
        $redirect_uri = $this->getApp()->config->twitch_redirect_url;

        $state = $this->getParam('state');

        if ($state === null) {
            $state = md5(time());
            $auth_url = sprintf(
                '%s?response_type=code&client_id=%s&redirect_uri=%s&state=%s&scope=%s',
                self::$TWITCH_AUTH_URL,
                $client_id,
                $redirect_uri,
                $state,
                "user:edit+channel:read:subscriptions+bits:read"
            );

            return $this->redirect($auth_url);
        }

        $code = $this->getParam('code');
        $token_url = 'https://id.twitch.tv/oauth2/token';
        $curly = new Client();

        //echo "CODE: $code<BR>";
        $response = $curly->post(sprintf(
            '%s?code=%s&client_id=%s&client_secret=%s&grant_type=authorization_code&redirect_uri=%s',
            $token_url,
            $code,
            $client_id,
            $client_secret,
            $redirect_uri
        ), [], ['Accept:', 'application/json']);

        if ($response['code'] == 200) {
            $token_response = json_decode($response['body'], 1);

            $access_token = $token_response['access_token'];

            $user_info = $this->getCurrentUser($curly, $client_id, $access_token);

            if ($user_info) {
                User::save($access_token, $user_info);
                return $this->redirect($this->getApp()->config->app_url . '/' . $user_info['twitch_user_login']);
            } else {
                echo "Error while trying to fetch user info.";
            }
        }

        echo "An error occurred.";
        print_r($response);
    }

    protected function getAuthorizeURL($client_id, $redirect_uri, $response_type, $scope): string
    {
        return sprintf(
            "%s?client_id=%s&redirect_uri=%s&response_type=%s&scope=%s",
            self::$TWITCH_AUTH_URL, $client_id, $redirect_uri, $response_type, $scope
        );
    }

    protected function getCurrentUser(Client $client, $client_id, $access_token)
    {
        $response = $client->get(
            'https://id.twitch.tv/oauth2/validate',
            $this->getHeaders($client_id, $access_token)
        );

        if ($response['code'] == 200) {
            return json_decode($response['body'], 1);
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