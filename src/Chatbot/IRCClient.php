<?php

namespace StreamWidgets\Chatbot;

class IRCClient
{
    protected $socket;
    protected $host;
    protected $port;
    protected $nick;
    protected $password;

    public function __construct($host, $port, $nick, $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->nick = $nick;
        $this->password = $password;
    }

    public function connect()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (socket_connect($this->socket, $this->host, $this->port) === FALSE) {
            return null;
        }

        $this->authenticate();
        $this->setNick();

    }

    public function authenticate()
    {
        $this->send(sprintf("PASS %s", $this->password));
    }

    public function setNick()
    {
        $this->send(sprintf("NICK %s", $this->nick));
    }

    public function getNick()
    {
        return $this->nick;
    }

    public function joinChannel($channel)
    {
        $this->send(sprintf("JOIN #%s", $channel));
    }

    public function sendMessage($channel, $message)
    {
        $this->send(sprintf("PRIVMSG #%s :%s", $channel, $message));
    }

    public function getLastError()
    {
        return socket_last_error($this->socket);
    }

    public function isConnected()
    {
        return !is_null($this->socket);
    }

    public function read($size = 256)
    {
        if (!$this->isConnected()) {
            return null;
        }

        return socket_read($this->socket, $size);
    }

    public function send($message)
    {
        if (!$this->isConnected()) {
            return null;
        }

        return socket_write($this->socket, $message . "\n");
    }

    public function close()
    {
        socket_close($this->socket);
    }
}