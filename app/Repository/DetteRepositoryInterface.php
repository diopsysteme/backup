<?php
namespace App\Repository;

interface DetteRepositoryInterface
{
    public function save(array $data);
    public function findById($id);
    public function createDette($request,$montant_total);
    public function getAll();
    public function getAll2();
    public function recupererDette($id, array $relations = []);

}
