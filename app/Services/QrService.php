<?php
namespace App\Services;

use BaconQrCode\Writer;
use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class QrService implements QrServiceInterface
{
    /**
     * Générer un QR code à partir de données
     *
     * @param string $data
     * @return string Le contenu du QR code au format Base64
     */
    public function generateQr($data)
    {
        // Créer un renderer avec une taille spécifique
        $renderer = new GDLibRenderer(400); // Taille de l'image (400x400 par exemple)
        
        // Passer le renderer à l'objet Writer
        $writer = new Writer($renderer);

        // Générer le QR code sous forme de chaîne de caractères
        $qrCodeData = $writer->writeString($data);

        // Encoder en Base64
        return base64_encode($qrCodeData);
    }
}
