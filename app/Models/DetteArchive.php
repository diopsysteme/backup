<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use MongoDB\Laravel\Eloquent\Model;

class DetteArchive extends Model
{
   protected $connection = 'mongodb';
   protected $fillable = ['client_id', 'nom','telephone','dettes'];

   public function setCollect($collect){
    $this->collection = $collect;
   }
   public function listAllCollections()
   {
       // Use the current MongoDB connection from the config
       $db = DB::connection($this->connection)->getMongoDB();

       // List all collections in the database
       $collections = $db->listCollections();

       // Return collection names
       return array_map(function ($collection) {
           return $collection->getName();
       }, iterator_to_array($collections));
   }
   public function dropCollection($collectionName)
   {
       $db = DB::connection($this->connection)->getMongoDB();

       // Drop the specified collection
       $db->dropCollection($collectionName);

       return "Collection {$collectionName} dropped successfully.";
   }
  
}
