<?php

namespace App\Services;

use TCPDF;

class PdfService implements PdfServiceInterface
{
    /**
     * Générer un PDF à partir de données HTML ou texte avec TCPDF
     *
     * @param string $htmlData Contenu HTML ou texte à convertir en PDF
     * @return string Le contenu du PDF en Base64
     */
    public function generatePdf($htmlData)
    {
        $pdf = new TCPDF();
    
        // Définition des métadonnées du document
        $pdf->SetCreator('Votre Application');
        $pdf->SetAuthor('Votre Société');
        $pdf->SetTitle('Facture de Consommation');
        $pdf->SetSubject('Facture de Consommation');
        
        // Définir les marges
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(TRUE, 20);
        
        // Ajouter une page
        $pdf->AddPage();
        

        $pdf->writeHTML($htmlData, true, false, true, false, '');
    
        // Retourner le contenu du PDF en tant que chaîne
        return $pdf->Output('facture.pdf', 'S');
    }
    
}
