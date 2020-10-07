<?php

namespace Chatbot;

use StreamWidgets\Chatbot\ChatbotService;

class InfoCommand extends AutoReplier
{
    public function getReplyMessage(string $author, string $channel, string $message, ChatbotService $chatbot):string
    {
        return $chatbot->bot_info['info'];
    }

    public function getName()
    {
        return 'info';
    }
}