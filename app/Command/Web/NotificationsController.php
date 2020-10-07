<?php

namespace App\Command\Web;

use StreamWidgets\Service\TwigProvider;
use StreamWidgets\WebController;

class NotificationsController extends WebController
{
    public function handle()
    {
        // output notifications from a file
        $file = $this->getApp()->config->notifications_file;

        if ($file && file_exists($file)) {
            $file = escapeshellarg($file);
            $line = `tail -n 1 $file`;

            /** @var TwigProvider $twig */
            $twig = $this->getApp()->twig;

            echo $twig->render("overlays/notifications.html.twig", ['message' => $line ]);
        }
    }
}