<?php

namespace Chatbot;

use StreamWidgets\Chatbot\ChatbotService;
use StreamWidgets\Chatbot\ChatbotCommandInterface;
use Minicli\Curly\Client;

class CaptureCommand implements ChatbotCommandInterface
{
    static $elephpants = [
        'purple',
        'pink',
        null,
        'white',
        'yellow',
        'golden',
        'green',
        'blue',
        'black',
        'red',
        null,
    ];

    public function handle(ChatbotService $chatbot, array $args = [])
    {
        $author = $args['author'];
        $phant = self::$elephpants[array_rand(self::$elephpants)];
        $message = "$author just captured an exemplar of $phant elephpant!";
        $channel = $args['channel'];

        $curly = new Client();
        $api_game_capture = 'http://' . $channel . '.swidgets.live/game?name=capture';
        $ping_url = sprintf(
            '%s&user=%s&phant=%s',
            $api_game_capture,
            $author,
            $phant
        );

        if ($phant !== null) {
            $response = $curly->get($ping_url);
        } else {
            $message = "$author tried, but couldn't capture an elephpant this time.";
        }

        $chatbot->getClient()->sendMessage($message, $channel);
    }

    public function getName()
    {
        return 'capture';
    }
}