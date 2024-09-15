<?php
namespace App\Services;

use App\Models\Client;

class ContentHtmlService {
    public static function generateHtml(array $tab) {
        // Utiliser la vue Blade pour générer le HTML
        return view('carte', compact('tab'))->render();
    }
}
