<?php

$font = 'C:/Windows/Fonts/arial.ttf';
$iconPath = __DIR__ . '/../public/icons/icon-512.png';
$outputDir = __DIR__ . '/../public/splash';

$screens = [
    [640, 1136],
    [750, 1334],
    [828, 1792],
    [1125, 2436],
    [1170, 2532],
    [1242, 2688],
    [1284, 2778],
    [1290, 2796],
    [1536, 2048],
    [1668, 2224],
    [1668, 2388],
    [2048, 2732],
];

$icon = imagecreatefrompng($iconPath);

foreach ($screens as [$width, $height]) {
    $img = imagecreatetruecolor($width, $height);

    for ($y = 0; $y < $height; $y++) {
        $r = 15 + (int) (5 * $y / $height);
        $g = 23 + (int) (65 * $y / $height);
        $b = 42 + (int) (77 * $y / $height);
        imageline($img, 0, $y, $width, $y, imagecolorallocate($img, $r, $g, $b));
    }

    $teal = imagecolorallocatealpha($img, 20, 184, 166, 75);
    $blue = imagecolorallocatealpha($img, 13, 110, 253, 82);
    $white = imagecolorallocate($img, 255, 255, 255);
    $muted = imagecolorallocate($img, 203, 213, 225);

    imagefilledellipse($img, (int) ($width * .22), (int) ($height * .18), (int) ($width * .8), (int) ($width * .8), $blue);
    imagefilledellipse($img, (int) ($width * .82), (int) ($height * .82), (int) ($width * .75), (int) ($width * .75), $teal);

    $iconSize = (int) min($width * .34, 260);
    $iconX = (int) (($width - $iconSize) / 2);
    $iconY = (int) (($height - $iconSize) / 2 - $height * .08);
    imagecopyresampled($img, $icon, $iconX, $iconY, 0, 0, $iconSize, $iconSize, 512, 512);

    $title = 'Portal DevTech';
    $subtitle = 'Tecnologia, noticias e inovacao';
    $titleSize = max(28, (int) ($width * .07));
    $subtitleSize = max(16, (int) ($width * .032));

    $titleBox = imagettfbbox($titleSize, 0, $font, $title);
    $titleWidth = $titleBox[2] - $titleBox[0];
    imagettftext($img, $titleSize, 0, (int) (($width - $titleWidth) / 2), $iconY + $iconSize + (int) ($height * .07), $white, $font, $title);

    $subtitleBox = imagettfbbox($subtitleSize, 0, $font, $subtitle);
    $subtitleWidth = $subtitleBox[2] - $subtitleBox[0];
    imagettftext($img, $subtitleSize, 0, (int) (($width - $subtitleWidth) / 2), $iconY + $iconSize + (int) ($height * .11), $muted, $font, $subtitle);

    imagepng($img, sprintf('%s/splash-%dx%d.png', $outputDir, $width, $height));
    imagedestroy($img);
}

imagedestroy($icon);
