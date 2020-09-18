<?php

namespace StreamWidgets\Service;

use Minicli\App;
use Minicli\ServiceInterface;

class LoggerService implements ServiceInterface
{
    protected $file;

    public function load(App $app)
    {
        $this->file = $app->config->notifications_file ?? __DIR__ . '/../../var/data/notifications.txt';
    }

    public function getResource()
    {
        return fopen($this->file, "a+");
    }

    public function write($content)
    {
        $resource = $this->getResource();
        fwrite($resource, $content . "\n");
        fclose($resource);
    }

    public function clear()
    {
        $file = fopen($this->file, "w+");
        fwrite($file, '');
        fclose($file);
    }
}