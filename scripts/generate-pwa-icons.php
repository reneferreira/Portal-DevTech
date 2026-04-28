<?php

$sizes = [
    96 => 'badge-96.png',
    192 => 'icon-192.png',
    512 => 'icon-512.png',
];

foreach ($sizes as $size => $file) {
    $img = imagecreatetruecolor($size, $size);
    imagealphablending($img, true);
    imagesavealpha($img, true);

    $teal = imagecolorallocate($img, 20, 184, 166);
    $dark = imagecolorallocate($img, 17, 24, 39);
    $white = imagecolorallocate($img, 255, 255, 255);
    $mint = imagecolorallocate($img, 167, 243, 208);

    for ($y = 0; $y < $size; $y++) {
        $r = 13 + (int) (7 * $y / $size);
        $g = 110 + (int) (74 * $y / $size);
        $b = 253 - (int) (214 * $y / $size);
        imageline($img, 0, $y, $size, $y, imagecolorallocate($img, $r, $g, $b));
    }

    imagefilledellipse($img, (int) ($size * .75), (int) ($size * .22), (int) ($size * .75), (int) ($size * .75), $teal);
    imagefilledellipse($img, (int) ($size * .2), (int) ($size * .85), (int) ($size * .7), (int) ($size * .7), $dark);

    $font = 5;
    $text = 'DT';
    $tw = imagefontwidth($font) * strlen($text);
    $th = imagefontheight($font);
    $scale = max(1, (int) ($size / 64));
    $x = (int) (($size - $tw * $scale) / 2);
    $y = (int) (($size - $th * $scale) / 2);

    for ($dx = 0; $dx < $scale; $dx++) {
        for ($dy = 0; $dy < $scale; $dy++) {
            imagestring($img, $font, $x + $dx, $y + $dy, $text, $white);
        }
    }

    imagefilledrectangle($img, (int) ($size * .2), (int) ($size * .73), (int) ($size * .8), (int) ($size * .78), $mint);
    imagepng($img, __DIR__ . '/../public/icons/' . $file);
    imagedestroy($img);
}

copy(__DIR__ . '/../public/icons/icon-512.png', __DIR__ . '/../public/icons/maskable-512.png');
copy(__DIR__ . '/../public/icons/icon-192.png', __DIR__ . '/../public/apple-touch-icon.png');
