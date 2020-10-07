<?php

namespace StreamWidgets\Chatbot;

class Socket
{
    protected $host;

    protected $port;

    protected $resource;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;

        $this->resource = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (socket_connect($this->resource, $host, $port) === FALSE) {
            return null;
        }
    }

    public function getLastError()
    {
        return socket_last_error($this->resource);
    }

    public function isConnected()
    {
        return !is_null($this->resource);
    }

    public function read($size = 256)
    {
        if (!$this->isConnected()) {
            return null;
        }

        return socket_read($this->resource, $size);
    }

    public function send($message)
    {
        if (!$this->isConnected()) {
            return null;
        }

        return socket_write($this->resource, $message . "\n");
    }

    public function close()
    {
        socket_close($this->resource);
    }
}