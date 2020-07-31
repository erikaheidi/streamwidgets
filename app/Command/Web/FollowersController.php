<?php

namespace App\Command\Web;

use Minicli\Curly\Client;
use StreamWidgets\WebController;
use Twig\Environment;

class FollowersController extends WebController
{
    public function handle()
    {
        $client_id = $this->getApp()->config->twitch_client_id;
        $access_token = $this->getApp()->config->twitch_access_token;

        if ($client_id === null OR $access_token === null) {
            echo "Missing Twitch credentials.";
            exit;
        }

        $client = new Client();

        //get user id
        $validate_url = 'https://id.twitch.tv/oauth2/validate';
        $validate_response = $client->get($validate_url, $this->getHeaders($client_id, $access_token));

        if ($validate_response['code'] == 200) {
            $user_info = json_decode($validate_response['body'], 1);
            $user_id = $user_info['user_id'];
        }

        if (!$user_id) {
            echo "NO USER ID FOUND";
            exit;
        }

        $url = sprintf("https://api.twitch.tv/helix/users/follows?to_id=%s", $user_id);

        $response = $client->get($url, $this->getHeaders($client_id, $access_token));

        if ($response['code'] == 200) {
            $followers_response = json_decode($response['body'], true);

            $limit = $this->getParam('limit') ?? 2;

            /** @var Environment $twig */
            $twig = $this->getApp()->twig;

            echo $twig->render('widgets/followers.html.twig', [
                'followers' => array_slice($followers_response['data'], 0, $limit)
            ]);

        } else {
            echo "ERROR!";
            var_dump($response);
        }
    }

    protected function getHeaders($client_id, $access_token)
    {
        return [
            "Client-ID: $client_id",
            "Authorization: Bearer $access_token"
        ];
    }
}