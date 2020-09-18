<?php

namespace StreamWidgets\Service;

use Minicli\App;
use Minicli\ServiceInterface;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class TwigService implements ServiceInterface
{
    /** @var Environment */
    protected $twig;

    public function load(App $app)
    {
        $loader = new FilesystemLoader($app->config->twig_templates_path);
        $this->twig = new Environment($loader);
        $this->twig->getLoader();
    }

    public function getEnvironment()
    {
       return $this->twig;
    }

    public function render($template, $data)
    {
        return $this->twig->render($template, $data);
    }
}