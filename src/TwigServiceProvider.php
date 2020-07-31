<?php

namespace StreamWidgets;

use Minicli\App;
use Minicli\ServiceInterface;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class TwigServiceProvider implements ServiceInterface
{
    /** @var Environment */
    protected $twig;

    public function load(App $app)
    {
        $loader = new FilesystemLoader($app->config->twig_templates_path);

        $this->twig = new Environment($loader, [
            //'cache' => $app->config->twig_cache_path,
        ]);
    }

    public function render($template, $data)
    {
        return $this->twig->render($template, $data);
    }
}