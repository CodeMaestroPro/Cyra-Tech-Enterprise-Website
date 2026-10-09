<?php

namespace App\Services;

use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCodeService extends BaseService
{
    public function svg(string $content, int $size = 168): string
    {
        $fill = Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(0, 82, 255));

        $renderer = new ImageRenderer(
            new RendererStyle($size, 1, null, null, $fill),
            new SvgImageBackEnd(),
        );

        $svg = (new Writer($renderer))->writeString($content);

        return trim((string) preg_replace('/<\?xml[^>]+\?>/', '', $svg));
    }
}
