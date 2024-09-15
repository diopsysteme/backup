<?php 
namespace App\Repository;

interface ArchiveRepositoryInterface
{
    // public function store(array $data);
    public function retrieve($filters,$collectionName);
    public function delete($id ,$collectionName);
    public function restore($filters);
}
