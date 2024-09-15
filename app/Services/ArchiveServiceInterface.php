<?php
namespace App\Services;

interface ArchiveServiceInterface
{


    public function archiveSoldedDettes(array $clients): void;
    public function getArchivedDettes(array $filters=[]): array; // Get all archived dettes with filtering
    public function restoreDetteById( $id); // Restore a specific archived dette
    public function restoreDettesByClientId(string $clientId): void; // Restore all dettes for a specific client
    public function restoreDettesByDate(string $date): void; // Restore dettes archived on a specific date
    public function displayArchivedDetteWithDetails(string $id): array; // Display details of a specific archived dette

}

