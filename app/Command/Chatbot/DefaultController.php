<?php

namespace App\Command\Chatbot;

use StreamWidgets\Chatbot\ChatbotService;
use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    public function handle()
    {
        $this->getPrinter()->info("Starting Minichat for Twitch...");

        /** @var ChatbotService $chatbot */
        $chatbot = $this->getApp()->chatbot;
        $client = $chatbot->getClient();
        $join_channels = $this->getApp()->config->chatbot_channels;

        if (!$client->connect($join_channels)) {
            $this->getPrinter()->error("It was not possible to connect.");
        }

        while (true) {
            $content = $client->read();

            if ($content === null) {
                sleep(5);
                continue;
            }

            if ($content['type'] === 'command') {
                $command = $content['command'];
                $this->getPrinter()->info("Received command: $command on #" . $content['channel']);

                if ($chatbot->botHasCommand($command)) {
                    $chatbot->runCommand(
                        $command, [
                            'author' => trim($content['nick']),
                            'message' => trim($content['message']),
                            'channel' => trim($content['channel'])
                    ]);
                    continue;
                } else {
                    $this->getPrinter()->info("Ignoring nonexistent command...");
                }
            }
        }
    }
}