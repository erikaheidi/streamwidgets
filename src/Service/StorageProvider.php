<?php

namespace StreamWidgets;

use Minicli\App;
use Minicli\Minicache\FileCache;
use Minicli\ServiceInterface;

class StorageService implements ServiceInterface
{
    /** @var FileCache */
    protected $resource;

    const CACHED_USERID = 'twitch_USERID';
    const CACHED_FOLLOWERS = 'twitch_FOLLOWERS';
    const CACHED_SUBS = 'twitch_SUBS';
    const CACHED_LEADERBOARD = 'twitch_leaderboard';

    public function load(App $app)
    {
        if (!$app->config->has('data_dir')) {
            throw new \Exception("Missing cache_dir config parameter.");
        }

        $this->resource = new FileStorage($app->config->data_dir, $app->config->cache_minutes ?? 1);
    }

    public function get($key)
    {
        return $this->resource->getCachedUnlessExpired($key);
    }

    public function save($content, $key)
    {
        $this->resource->save($content, $key);
    }
}