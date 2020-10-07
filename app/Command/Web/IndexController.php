<?php

namespace App\Command\Web;

use StreamWidgets\WebController;

class IndexController extends WebController
{
    public function handle()
    {
        echo "index";
    }
}