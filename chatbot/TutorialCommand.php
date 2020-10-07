<?php

namespace Chatbot;

use StreamWidgets\Chatbot\ChatbotService;

class TutorialCommand extends AutoReplier
{
    public function getReplyMessage(string $author, string $channel, string $message, ChatbotService $chatbot):string
    {
        $keyword = trim($message);
        $tutorials = $chatbot->bot_info['featured_tutorials'];

        if (key_exists($keyword, $tutorials)) {
            $reply = "@$author encontrei 1 tutorial marcado como *$keyword*: " . $tutorials[$keyword];
        } else {
            $reply = "@$author nenhum tutorial encontrado com a tag *$keyword*.";
        }

        return $reply;
    }

    public function getName()
    {
        return 'tutorial';
    }
}