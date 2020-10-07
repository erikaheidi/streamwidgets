<?php

namespace StreamWidgets\Twitch;

use StreamWidgets\Chatbot\IRCClient;
use StreamWidgets\Service\LoggerProvider;

class TwitchChatClient
{
    static $host = "irc.chat.twitch.tv";
    static $port = "6667";
    static $pong_response = ":tmi.twitch.tv";

    /** @var IRCClient */
    protected $twitch_client;

    /** @var LoggerProvider */
    protected $notifications;

    public function __construct($twitch_user, $twitch_oauth)
    {
        $this->twitch_client = new IRCClient(self::$host, self::$port, $twitch_user, $twitch_oauth);
    }

    public function connect(array $channels = [])
    {
        $client = $this->twitch_client;
        $client->connect();

        $client->joinChannel($this->twitch_client->getNick());

        if (count($channels)) {
            foreach ($channels as $channel) {
                echo "joining channel $channel...\n";
                $client->joinChannel($channel);
            }
        }

        if (!$client->isConnected()) {
            return false;
        }

        return true;
    }

    /**
     * Sends a message in the current stream channel (same as nickname)
     * @param $message
     * @param string $channel
     */
    public function sendMessage($message, $channel = null)
    {
        if (!$channel) {
            $channel = $this->twitch_client->getNick();
        }

        $this->twitch_client->sendMessage($channel, $message);
    }

    /**
     * Reads content from the client
     * @param bool $debug
     * @return array|null
     */
    public function read($debug = false)
    {
        $content = $this->twitch_client->read(512);
        $type = 'message';
        $author = "";
        $message = "";
        $command = "";
        $channel = "";

        if ($debug) {
            echo $content;
        }

        //is it a ping?
        if (strstr($content, 'PING')) {
            $this->twitch_client->send('PONG ' . self::$pong_response);
            return null;
        }

        //is it an actual msg?
        if (strstr($content, 'PRIVMSG')) {
            $parsed = $this->parseMessage($content);
            $author = $parsed['nick'];
            $message = $parsed['message'];
            $channel  = $parsed['channel'];

            //is there a chat command in it?
            if ($message[0] == '!') {
                $type = 'command';
                $command = $this->parseCommand($message);
                $message = str_replace("!$command", '', $message);
            }
        }

        return [
            'type' => $type,
            'content' => $content,
            'nick' => $author,
            'message' => $message,
            'command' => $command,
            'channel' => $channel
        ];
    }

    /**
     * Parses a message to obtain nick and content
     * @param string $raw_message
     * @return array
     */
    protected function parseMessage($raw_message)
    {
        $parts = explode(":", $raw_message, 3);
        $nick_parts = explode("!", $parts[1]);
        $channel_parts = explode("#", $parts[1]);

        $nick = $nick_parts[0];
        $message = $parts[2];
        $channel = $channel_parts[1];

        return [ 'nick' => $nick, 'message' => $message, 'channel' => $channel ];
    }

    /**
     * Parses a message to extract command
     * @param string $message
     * @return string
     */
    protected function parseCommand($message)
    {
        //!test for instance
        $explode = explode(' ', $message, 2);

        return trim(substr($explode[0], 1));
    }
}