<?php

namespace App\Http\Controllers;


use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    function __invoke()
    {
        $canvas = Image::canvas(720, 650, '#ffffff');

        $cover = Image::make(base_path('/public/images/default.png'));
        $cover->resize(720, 480);
        $canvas->insert($cover);

        $qrcode = Image::make(base_path('/public/images/qrcode.jpeg'));
        $qrcode->resize(160, 160);
        $canvas->insert($qrcode, 'bottom-right', 10, 10);

        $fingerprint = Image::make(base_path('/public/images/fingerprint.png'));
        $fingerprint->resize(120, 120);
        $canvas->insert($fingerprint, 'bottom-left', 25, 25);

        $canvas->text('The quick brown fox jumps over the lazy dog.');

        $canvas->text('常按识别图片', 160, 580, function ($font) {
            $font->file(base_path('/public/images/hei.ttf'));
            $font->size(48);
            $font->color('#333');
        });

        $canvas->text('一二三四五六七八九十中国人民解放军', 50, 50, function ($font) {
            $font->file(base_path('/public/images/hei.ttf'));
            $font->size(36);
            $font->color('#ffffff');
        });

// use callback to define details
        $canvas->text('foo', 0, 0, function ($font) {
            //$font->file('foo/bar.ttf');
            $font->size(24);
            $font->color('#fdf6e3');
            $font->align('center');
            $font->valign('top');
            //$font->angle(45);
        });

        return $canvas->response();

    }
}
