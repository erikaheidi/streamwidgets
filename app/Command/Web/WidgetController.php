<?php

namespace App\Command\Web;

use Minicli\Command\CommandController;
use StreamWidgets\Component\TextImage;

class WidgetController extends CommandController
{
    // outputs dynamic widgets
    public function handle()
    {
        $params = $this->getParams();
        //var_dump($params);
        $mytext = $params['text'] ?? "Dev and Chill";

        //using a static value to test it out first
        $text_image = new TextImage();

        $image = $text_image->build($mytext);

        header('Content-type: image/png');
        echo $image->getImagesBlob();
    }
}