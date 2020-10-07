<?php

namespace Chatbot;

use StreamWidgets\Chatbot\ChatbotCommandInterface;
use StreamWidgets\Chatbot\ChatbotService;
use StreamWidgets\FileStorage;

class RaffleCommand implements ChatbotCommandInterface
{
    const RAFFLE_LOG = "GAME_RAFFLE";
    const RAFFLE_DIR  = "raffle";

    // !sorteio, !sorteio
    public function handle(ChatbotService $chatbot, array $args = [])
    {
        $storage = new FileStorage($chatbot->data_dir . '/' . self::RAFFLE_DIR);
        $participants = $storage->getCached(self::RAFFLE_LOG);

        if ($participants !== null) {
            $participants = json_decode($participants, 1);
        } else {
            $participants = [];
        }


        $author = $args['author'];
        $channel = $args['channel'];
        //$message = $args['message'];

        if (!in_array($author, $participants)) {
            $participants[] = $author;
        }

        $storage->save(json_encode($participants), self::RAFFLE_LOG);

        $reply = "$author você está agora participando do sorteio!";
        $chatbot->getClient()->sendMessage($reply, $channel);
    }

    public function getName()
    {
        return 'sorteio';
    }
}