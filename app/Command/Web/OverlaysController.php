<?php

namespace App\Command\Web;

use StreamWidgets\WebController;
use Twig\Environment;

class OverlaysController extends WebController
{
    public function handle()
    {
        /** @var Environment $twig */
        $twig = $this->getApp()->twig;

        $overlay = "links";

        if ($this->hasParam('name')) {
            $overlay = $this->getParam('name');
        }

        echo $twig->render("overlays/$overlay.html.twig", $this->getParams());
    }

}