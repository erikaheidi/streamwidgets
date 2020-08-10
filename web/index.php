<?php

require __DIR__ . '/../vendor/autoload.php';

use Minicli\App;
use StreamWidgets\TwigServiceProvider;
use StreamWidgets\StorageService;
use StreamWidgets\LoggerService;
use StreamWidgets\TwitchServiceProvider;
use StreamWidgets\GamesServiceProvider;

use App\Games\Capture;

$app = new App(require __DIR__ . '/../config.php');
$app->addService('twig', new TwigServiceProvider());
$app->addService('storage', new StorageService());
$app->addService('logger', new LoggerService());
$app->addService('twitch', new TwitchServiceProvider());

$games = new GamesServiceProvider();
$games->registerGame('capture', new Capture());

$app->addService('games', $games);

$parts = explode('/', $_SERVER['PATH_INFO']);
$subcommand = "default";

if (count($parts) && $parts[1] !== '') {
    $subcommand = $parts[1];
}

$command_path = $app->config->app_path . '/Web/' . ucfirst($subcommand) . 'Controller.php';
if (!file_exists($command_path)) {
    http_response_code(404);
    returnJson(404, 'Not Found.');
    exit;
}

$params = array_key_exists('QUERY_STRING', $_SERVER) ? explode('&', $_SERVER['QUERY_STRING']) : [];

$app->runCommand(array_merge(['streamaru', 'web', $subcommand], $params));

function returnJson($code, $message)
{
    echo json_encode(['code' => $code, 'message' => $message]);
}