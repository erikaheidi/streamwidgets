<?php

require __DIR__ . '/../vendor/autoload.php';

use Minicli\App;
use StreamWidgets\Service\TwigProvider;
use StreamWidgets\Service\StorageProvider;
use StreamWidgets\Service\LoggerProvider;
use StreamWidgets\Twitch\TwitchService;
use StreamWidgets\Service\GamesProvider;
use App\Games\Capture;

$config = require __DIR__ . '/../config.php';
$user_config = [];
$controller = "index";
$user = null;

$anon_routes = [
    'auth'
];

$parts = explode('/', $_SERVER['REQUEST_URI']);

if (count($parts) && $parts[1] !== '') {
    $path = explode('?', $parts[1]);

    if (in_array($path[0], $anon_routes)) {
        $controller = $path[0];
    } else {
        $user = $path[0];
        $controller = "user";
    }
}

if (count($parts) && $parts[2] !== null) {
    $controller = $parts[2];
}

$app = new App($config);

$app->addService('twig', new TwigProvider());
$app->addService('storage', new StorageProvider());
$app->addService('logger', new LoggerProvider());
$app->addService('twitch', new TwitchService());

$games = new GamesProvider();
$games->registerGame('capture', new Capture());
$app->addService('games', $games);

$command_path = $app->config->app_path . '/Web/' . ucfirst($controller) . 'Controller.php';
if (!file_exists($command_path)) {
    http_response_code(404);
    returnJson(404, 'Not Found.');
    exit;
}

$params = array_key_exists('QUERY_STRING', $_SERVER) ? explode('&', $_SERVER['QUERY_STRING']) : [];
if ($user) {
    $params[] = "user=" . $user;
}

$app->runCommand(array_merge(['streamwidgets', 'web', $controller], $params));

function returnJson($code, $message)
{
    echo json_encode(['code' => $code, 'message' => $message]);
}