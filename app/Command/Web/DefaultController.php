<?php

namespace App\Command\Web;

use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    public function handle()
    {
        echo "testing main endpoint";
    }
}