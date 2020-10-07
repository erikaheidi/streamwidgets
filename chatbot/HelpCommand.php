<?php

namespace Chatbot;

use StreamWidgets\Chatbot\ChatbotService;

class HelpCommand extends AutoReplier
{
    public function getReplyMessage(string $author, string $channel, string $message, ChatbotService $chatbot):string
    {
        $reply = "@$author comandos disponíveis: ";
        $commands = $chatbot->getCommandList();

        var_dump($commands);

        foreach ($commands as $item) {
            $reply .= '!' . $item . ' ';
        }

        return $reply;
    }

    public function getName()
    {
        return 'help';
    }
}