<?php

namespace Chatbot;

use StreamWidgets\Chatbot\ChatbotCommandInterface;
use StreamWidgets\Chatbot\ChatbotService;

abstract class AutoReplier implements ChatbotCommandInterface
{
    public function handle(ChatbotService $chatbot, array $args = [])
    {
        $author = $args['author'];
        $channel = $args['channel'];
        $message = $args['message'];

        $reply = $this->getReplyMessage($author, $channel, $message, $chatbot);

        $chatbot->getClient()->sendMessage($reply, $channel);
    }

    abstract public function getReplyMessage(string $author, string $channel, string $message, ChatbotService $chatbot): string;
}