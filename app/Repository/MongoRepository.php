<?php 
namespace App\Repository;

use App\Models\DetteArchive;

class MongoRepository implements ArchiveRepositoryInterface
{
    protected $model;

    public function __construct(DetteArchive $model)
    {
        $this->model = $model;
    }

    /**
     * Store a new archived debt record in MongoDB.
     *
     * @param array $data
     * @param string $collectionName
     */
    public function store(array $data)
    {
        $today = now()->format('Y_m_d');
        $collectionName = 'dettes_' . $today;
        $this->model->setCollect($collectionName);

        $this->model->create($data);
    }

    /**
     * Retrieve a specific archived debt based on filters.
     *
     * @param array $filters
     * @param string $collectionName
     * @return mixed
     */
    public function retrieve($filters, $collectionName, $action = 'retrieve')
    {
        $this->model->setCollect($collectionName);
    
        if (empty($filters)) {
            $data = $this->model->get()->toArray();
    
            if ($action === 'delete') {
                $this->model->dropCollection($collectionName);
            }
    
            // If not deleting, return all documents
            return $data??null;
        }
    
        $query = [];
    
        if (isset($filters['client_id'])) {
            $query['client_id'] = $filters['client_id'];
        }
    
        // Add dette_id filter if provided
        if (isset($filters['dette_id'])) {
            $query['dettes.dette_id'] = $filters['dette_id'];
        }
    
        // If the action is 'delete', perform the delete operation
        if ($action === 'delete') {
            // dd("ff");
            return $this->model->where($query)->delete(); // Perform deletion based on the filters
        }
    
        // If the action is 'retrieve', proceed with data retrieval
        $results = $this->model->where($query)->get()->toArray();
        // dd($results);
        // Filter out clients with empty debts if a dette_id filter was applied
        if (isset($filters['dette_id'])) {
            $filteredResults = [];
    
            foreach ($results as $clientData) {
                if (isset($clientData['dettes'])) {
                    // Filter debts for the specific dette_id
                    $filteredDebts = array_filter($clientData['dettes'], function ($dette) use ($filters) {
                        return isset($dette['dette_id']) && $dette['dette_id'] == $filters['dette_id'];
                    });
    
                    // Include the client in the result if there are matching debts
                    if (!empty($filteredDebts)) {
                        $clientData['dettes'] = $filteredDebts;
                        $filteredResults[] = $clientData;
                    }
                }
            }
    
            return ($filteredResults);
        }
    
        return ($results);
    }
    


    
    public function retrieveAll($filters,$action="retrieve"){
       $collections= $this->model->listAllCollections();
       $debts=[];
    //   declare($debts);
       foreach($collections as $collection){
        $debts[]=$this->retrieve($filters,$collection,$action);
       }
       return ($debts);

    }
    public function deleteDebt($detteId, $collectionName)
    {
        $this->model->setCollect($collectionName);
    
        $results = $this->retrieve($detteId, $collectionName);
    // dd($results);
        if($results){foreach ($results as $document) {
            $clientId = $document['client_id'];
    
            $this->model->where('client_id', $clientId)
                        ->update([
                            '$pull' => ['dettes' => ['dette_id' => $detteId]]
                        ]);
        }}
    }
    
    public function deleteByIdDebt($id){
        $collections= $this->model->listAllCollections();
        foreach($collections as $collection){
         $this->deleteDebt($id,$collection);
        }
    }

    /**
     * Delete an archived debt by its ID.
     *
     * @param string $id
     * @param string $collectionName
     */
    public function delete( $id,  $collectionName=null )
    {
        $this->model->setCollect($collectionName);
        $this->model->where('_id', $id)->delete();
    }

    /**
     * Restore an archived debt based on filters.
     * Deletes the document after restoration.
     *
     * @param array $filters
     * @param string $collectionName
     * @return mixed|null
     */
    public function restore($id)
    {
        $filter["dette_id"]=$id;
        $document = $this->retrieveAll($filter);

// dd($document);
        $this->deleteByIdDebt($id);
        return $document??null;
    }
    public function restoreByDate($collectionName):array|null 
    {
        $document = $this->retrieve([],$collectionName);
        $this->retrieve([],$collectionName,"delete");
        return $document??null;
    }
    public function restoreByClientId($id)
    {
        $filter["client_id"] = $id;
        $document = $this->retrieveAll($filter);

        $this->retrieveAll($filter,"delete");
        return $document??null;
    }

}
