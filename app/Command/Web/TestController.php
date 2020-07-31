<?php

namespace App\Command\Web;

use Minicli\Command\CommandController;

class TestController extends CommandController
{
    public function handle()
    {
        echo "testing the test endpoint";
    }
}