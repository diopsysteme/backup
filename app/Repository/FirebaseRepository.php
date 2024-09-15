<?php
namespace App\Repository;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class FirebaseRepository implements ArchiveRepositoryInterface
{
    protected $database;


    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials.key_file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $this->database = $factory->createDatabase();
    }

    public function store($data, $path)
    {
        $this->database->getReference($path)->push($data);
    }


    public function retrieve($filters, $path, $action = 'retrieve')
    {
        // Get the reference from Firebase at the given path
        $query = $this->database->getReference($path);
        $snapshot = $query->getSnapshot();


        // If data exists, proceed
        if ($snapshot->exists()) {
            $values = $snapshot->getValue();
            $filteredData = [];
            if (empty($filters) && $action == 'delete') {
                $query->remove();
            }
            if (isset($filters['client_id'])) {
                $clientId = $filters['client_id'];
                
                foreach ($values as $key => $clientData) {
                    if (isset($clientData['client_id']) && $clientData['client_id'] == $clientId) {
                        // Apply the dette_id filter if provided
                        if (isset($filters['dette_id'])) {
                            $detteId = $filters['dette_id'];
                            if (isset($clientData['dettes'])) {
                                $clientData['dettes'] = array_filter($clientData['dettes'], function ($dette) use ($detteId) {
                                    return isset($dette['dette_id']) && $dette['dette_id'] == $detteId;
                                });
                                
                                // Include the client in the result if there are matching debts
                                if (!empty($clientData['dettes'])) {
                                    $filteredData[$key] = $clientData;
                                }
                            }
                        } else {
                            // If there's no dette_id filter, add the client data
                            $filteredData[$key] = $clientData;
                        }
                        
                        // If action is 'delete', remove the data from Firebase
                        if ($action === 'delete') {
                            $this->database->getReference($path . '/' . $key)->remove();
                        }
                    }
                }
            } else {
                // No client_id filter, apply dette_id filter if provided
                if (isset($filters['dette_id'])) {
                    $detteId = $filters['dette_id'];
                    foreach ($values as $key => $clientData) {
                        if (isset($clientData['dettes'])) {
                            $clientData['dettes'] = array_filter($clientData['dettes'], function ($dette) use ($detteId) {
                                return isset($dette['dette_id']) && $dette['dette_id'] == $detteId;
                            });
                            
                            // Include the client if there's at least one matching debt
                            if (!empty($clientData['dettes'])) {
                                $filteredData[$key] = $clientData;
                                
                                // If action is 'delete', remove the data from Firebase
                                if ($action === 'delete') {
                                    $this->database->getReference($path . '/' . $key)->remove();
                                }
                            }
                        }
                    }
                } else {
                    // No filters applied, return all data
                    $filteredData = $values;
                    // dd($values);
                }
            }
            
            return ($filteredData);
        }
        
        return [];
    }




    public function retrieveAll($filter, $action = 'retrieve')
    {
        // dd("dd");
        $query = $this->database->getReference();
        $snapshot = $query->getSnapshot();
        if(!$snapshot->hasChildren()){
            return [];
        }
        $debts = [];
        // dd($query);
        foreach ($query->getChildKeys() as $key) {
            // dd($debts);
            $debts[] = $this->retrieve($filter, $key, $action);
        }
        // dd("dd");
        return $debts;
    }

    public function delete($detteId, $path)
    {
        $query = $this->database->getReference($path);
        $snapshot = $query->getSnapshot();

        if ($snapshot->exists()) {
            $values = $snapshot->getValue();
            foreach ($values as $key => $clientData) {
                if (isset($clientData['dettes'])) {
                    foreach ($clientData['dettes'] as $index => $dette) {
                        if (isset($dette['dette_id']) && $dette['dette_id'] == $detteId) {
                            unset($clientData['dettes'][$index]);
                            $this->database->getReference($path . '/' . $key)->update(['dettes' => array_values($clientData['dettes'])]);
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    public function deteleByDebtId($id)
    {
        $query = $this->database->getReference();
        $snapshot = $query->getSnapshot();
        if(!$snapshot->hasChildren()){
            return [];
        }
        foreach ($query->getChildKeys() as $key) {
            $this->delete($id, $key);
        }
    }
    public function restore($id)
    {
        $filter["dette_id"] = $id;
        $document = $this->retrieveAll($filter);


        $this->deteleByDebtId($id);
        return $document ?? null;

    }
    public function restoreByDate($collectionName): array|null
    {
        $document = $this->retrieve([], $collectionName);
        $this->retrieve([], $collectionName, "delete");
        return $document ?? null;
    }
    public function restoreByClientId($id)
    {
        $filter["client_id"] = $id;
        $document = $this->retrieveAll($filter);


        $this->retrieveAll($filter, "delete");
        return $document ?? null;
    }
}
