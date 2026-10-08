<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;

class QrCodeService
{
    public static function dataUri(string $text): string
    {
        $qr = new QRCode([
            'eccLevel' => 'M',
            'outputBase64' => true,
            'addQuietzone' => true,
            'quietzoneSize' => 2,
            'scale' => 8,
        ]);

        return (string) $qr->render($text);
    }
}
