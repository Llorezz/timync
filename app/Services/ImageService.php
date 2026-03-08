<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    /**
     * Comprime y guarda una imagen. Devuelve el path relativo.
     * @param UploadedFile $file
     * @param string $carpeta  (ej: 'portadas', 'galeria', 'servicios')
     * @param int $maxWidth    ancho máximo en px
     * @param int $quality     calidad JPEG 0-100
     */
    public static function guardar(UploadedFile $file, string $carpeta, int $maxWidth = 1200, int $quality = 75): string
    {
        $imagen = Image::read($file);

        // Redimensionar solo si es más ancha que el máximo
        if ($imagen->width() > $maxWidth) {
            $imagen->scaleDown(width: $maxWidth);
        }

        // Nombre único con extensión jpg siempre
        $nombre = $carpeta . '/' . uniqid() . '.jpg';

        // Guardar comprimido en disco público
        Storage::disk('public')->put(
            $nombre,
            $imagen->toJpeg(quality: $quality)
        );

        return $nombre;
    }
}
