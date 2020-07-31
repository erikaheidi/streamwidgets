<?php

namespace StreamWidgets\Component;

use Imagick;
use ImagickPixel;
use ImagickDraw;

class TextImage extends WidgetComponent
{
    public function build(string $content): Imagick
    {
        $image = $this->getResource();
        $image->setFormat($this->getFormat());

        $pixel = new ImagickPixel($this->getBackground());

        $draw = new ImagickDraw();
        $draw->setFillColor($this->getColor());
        $draw->setFont($this->getFont());
        $draw->setFontSize($this->getFontSize());

        $metrics = $image->queryFontMetrics($draw, $content);

        $image->newImage($metrics['textWidth'], $metrics['textHeight'], $pixel);
        $image->annotateImage($draw, 0, $this->getFontSize(), 0, $content);
        $draw->destroy();

        return $image;
    }
}