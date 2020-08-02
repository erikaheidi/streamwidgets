<?php

namespace App\Command\Cache;

use Minicli\Command\CommandController;

class ClearController extends CommandController
{
    public function handle()
    {
        $cache_dir = $this->getApp()->config->data_dir;

        foreach (glob($cache_dir . "/*.json") as $cache_file) {
            @unlink($cache_file);
        }

        $this->getPrinter()->info("Cache cleared.");
    }
}