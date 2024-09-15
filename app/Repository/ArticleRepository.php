<?php

namespace App\Repository;

interface ArticleRepository
{
    public function all();
    public function filter($request);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function updateAll($updated);
}
