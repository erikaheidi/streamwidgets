<?php

namespace App\Command\Web;

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

        /**
         * Twitch redirects you back to a url such as this: http://localhost:8000/twitch#access_token=TOKEN&scope=user%3Aedit&token_type=bearer
         * We can't obtain the # part of the URL from the back-end, so you'll have to check the url to get your token.
        **/

        echo "To obtain your access token, click the link below and authorize the application. You will be redirected back to this page.<br><br>";
        echo "When you're back to this page, check the browser URL and you will find your access token like this:</br>http://localhost:8000/twitch#access_token=<strong>YOUR_UNIQUE_ACCESS_TOKEN</strong>&scope=user...<br><br>";
        echo "Copy your access code to your config.php and keep it safe.";

        $client_id = $this->getApp()->config->twitch_client_id;
        $redirect_uri = $this->getApp()->config->twitch_redirect_url;
        $response_type = 'token';
        $scope = 'user:edit+channel:read:subscriptions+bits:read';

        echo "<br><br><strong>Authorize the app in the following link:</strong><br>";
        $url = $this->getAuthorizeURL($client_id, $redirect_uri, $response_type, $scope);

        echo '<a href="' . $url . '">' . $url . '</a>';
    }

    protected function getAuthorizeURL($client_id, $redirect_uri, $response_type, $scope): string
    {
        return sprintf(
            "%s?client_id=%s&redirect_uri=%s&response_type=%s&scope=%s",
            self::$TWITCH_AUTH_URL, $client_id, $redirect_uri, $response_type, $scope
        );
    }
}