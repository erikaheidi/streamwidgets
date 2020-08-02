<?php

namespace App\Command\Cache;

use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    public function handle()
    {
        $this->getPrinter()->info("Storage cache set to " . $this->getApp()->config->data_dir);
    }
}