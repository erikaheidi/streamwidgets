<?php

namespace StreamWidgets;

use Minicli\Command\CommandController;

abstract class WebController extends CommandController
{
    public function sessionGet($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function sessionSet($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function redirect($page)
    {
        header("Location: $page");
        exit;
    }
}