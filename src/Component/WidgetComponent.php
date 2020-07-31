<?php

namespace StreamWidgets\Component;

use Imagick;

class WidgetComponent
{
    /** @var array */
    protected $settings;

    /** @var Imagick $resource */
    protected $resource;

    const default_font = 'Bookman-Demi';
    const default_font_size = 32;
    const default_background = 'transparent';
    const default_color = 'white';
    const default_format = 'png';

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public function getFont()
    {
        return $this->get('font') ?? self::default_font;
    }

    public function getFontSize()
    {
        return $this->get('font_size') ?? self::default_font_size;
    }

    public function getColor()
    {
        return $this->get('color') ?? self::default_color;
    }

    public function getBackground()
    {
        return $this->get('background') ?? self::default_background;
    }

    public function getFormat()
    {
        return $this->settings['format'] ?? self::default_format;
    }

    public function getResource()
    {
        if ($this->resource === null) {
            $this->resource = new Imagick();
        }

        return $this->resource;
    }

    public function get($settings_key)
    {
        return $this->settings[$settings_key] ?? null;
    }

    public function set($settings_key, $value)
    {
        $this->settings[$settings_key] = $value;
    }
}