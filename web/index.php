<?php

require __DIR__ . '/../vendor/autoload.php';

use Minicli\App;
use StreamWidgets\Service\TwigProvider;
use StreamWidgets\Service\StorageProvider;
use StreamWidgets\Service\LoggerProvider;
use StreamWidgets\Service\TwitchProvider;
use StreamWidgets\Service\GamesProvider;

use App\Games\Capture;

$credentials_path = __DIR__ . '/../.users/';
$config = require __DIR__ . '/../config.php';
$user_config = [];
$controller = "user";

$parts = explode('/', $_SERVER['REQUEST_URI']);

if (count($parts) && $parts[1] !== '') {
    $path = explode('?', $parts[1]);
    $controller = $path[0];
}

$anon_routes = [
    'index',
    'about',
    'auth',
];

if ($_SERVER['HTTP_HOST'] && !in_array($controller, $anon_routes)) {
    $subdomain = explode('.', $_SERVER['HTTP_HOST'])[0];

    if (!$subdomain) {
        //index page
        echo "Swidgets - sweeet widgets for live streaming on Twitch.";
        exit;
    }

    if (!is_file($credentials_path . $subdomain . '.json')) {
        //user doesnt exist. show auth button later on
        echo "User \"$subdomain\" not found.";
        echo "Are you $subdomain? To use Swidgets, you must <a href='/auth'>authorize us on Twitch</a>.";
        exit;
    }

    $user_config = json_decode(file_get_contents($credentials_path . $subdomain . '.json'), 1);
}

$config = array_merge($user_config, $config);
$app = new App($config);

$app->addService('twig', new TwigProvider());
$app->addService('storage', new StorageProvider());
$app->addService('logger', new LoggerProvider());
$app->addService('twitch', new TwitchProvider());

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

$app->runCommand(array_merge(['streamwidgets', 'web', $controller], $params));

function returnJson($code, $message)
{
    echo json_encode(['code' => $code, 'message' => $message]);
}