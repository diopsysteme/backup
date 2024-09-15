<?php
namespace App\Services;

use App\Models\DetteArchive;

class MongoDBArchiveService implements ArchiveServiceInterface
{
    public function archiveSoldedDettes(array $clients): void
    {
        $today = now()->format('Y_m_d');
        $collectionName = 'dettes_' . $today;

        foreach ($clients as $client) {
            $detteArchive = new DetteArchive();
            $detteArchive->setCollect($collectionName);

            $detteArchive->create([
                'client_id' => $client['id'],
                'nom' => $client['nom'],
                'telephone' => $client['telephone'],
                'dettes' => $client['dettes'],
            ]);
        }
    }
    public function getArchivedDettes(array $filters=[]): array
    {
        $query = DetteArchive::query();
        return $query->get()->toArray();
        
        if (isset($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }

        if (isset($filters['date'])) {
            $query->setCollection('dettes_' . $filters['date']);
        }

    }

    public function restoreDetteById( $id): void
    {
        $dette = DetteArchive::find($id);

        // if ($dette) {
        //     $dette->delete();
        //     // Optionally, add the code to restore to the main collection
        // }
    }

    public function restoreDettesByClientId(string $clientId): void
    {
        $dettes = DetteArchive::where('client_id', $clientId)->get();

        // foreach ($dettes as $dette) {
        //     $dette->delete();
        // }
    }

    public function restoreDettesByDate(string $date): void
    {
        $collectionName = 'dettes_' . $date;
        $dettes = DetteArchive::setCollection($collectionName)->get();

        // foreach ($dettes as $dette) {
        //     $dette->delete();
        // }
    }

    public function displayArchivedDetteWithDetails(string $id): array
    {
        $dette = DetteArchive::find($id);
        return $dette ? $dette->toArray() : [];
    }
}
