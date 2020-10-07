<?php

namespace StreamWidgets;

use Minicli\Minicache\FileCache;

class FileStorage extends FileCache
{
    /**
     * @param string $unique_identifier
     * @return string
     */
    public function getCacheFile($unique_identifier)
    {
        return $this->cache_dir . '/' . $unique_identifier . '.json';
    }
}