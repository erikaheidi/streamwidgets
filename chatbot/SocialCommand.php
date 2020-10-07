<?php

namespace Chatbot;


use StreamWidgets\Chatbot\ChatbotService;

class SocialCommand extends AutoReplier
{
    public function getReplyMessage(string $author, string $channel, string $message, ChatbotService $chatbot):string
    {
        $message = "Siga a Erika nas redes sociais: ";

        foreach ($chatbot->bot_info['social'] as $key => $info) {
            $message .= $key . ': ' . $info . ', ';
        }

        $message .= 'e aqui você já sabe: é twitch.tv/erikaheidi ;-)';

        return $message;
    }

    public function getName()
    {
        return 'social';
    }
}