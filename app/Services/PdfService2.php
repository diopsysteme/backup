<?php
namespace App\Services;
use Dompdf\Dompdf;

class PdfService2 implements PdfServiceInterface
{
    public function generatePdf($htmlData)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($htmlData);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}
