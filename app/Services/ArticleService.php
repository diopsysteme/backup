<?php

namespace App\Services;

interface ArticleService
{
    public function getAllArticles();
    public function getArticleById(int $id);
    public function createArticle(array $data);
    public function updateArticle(int $id, array $data);
    public function deleteArticle(int $id);
   public function approveById($id, $qtstock);

   public function approveArticles($articlesData);
}
