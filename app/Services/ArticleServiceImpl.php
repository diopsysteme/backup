<?php

namespace App\Services;

use App\Repository\ArticleRepository;

class ArticleServiceImpl implements ArticleService
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }
    public function getAllArticles()
    {
        $request = request();
        return $this->articleRepository->filter($request);
    }

    public function getArticleById(int $id)
    {
        return $this->articleRepository->findById($id);
    }

    public function createArticle(array $data)
    {
        return $this->articleRepository->create($data);
    }

    public function updateArticle(int $id, array $data)
    {
        return $this->articleRepository->update($id, $data);
    }

    public function deleteArticle(int $id)
    {
        return $this->articleRepository->delete($id);
    }
    public function findById($id)
    {
        return $this->articleRepository->findById($id);
    }



    public function approveArticles($articlesData)
    {
        $errors = [];
        $updated = [];

        foreach ($articlesData as $articleData) {
            if (!isset($articleData['id']) || !isset($articleData['quantite'])) {
                $errors[] = ['article' => $articleData, 'error' => 'ID ou quantité manquants'];
                continue;
            }

            $article = $this->articleRepository->findById($articleData['id']);

            if (!$article || $articleData['quantite'] <= 0) {
                $errors[] = ['article' => $articleData, 'error' => !$article ? 'Article introuvable' : 'Quantité invalide'];
            } else {
                $updated[] = [
                    "id"=>$article->id,
                    "qtstock"=>$article->qtstock,
                    "article"=>$article
                ];
            }
        }

        if (!empty($updated)) {
            $this->articleRepository->updateAll($updated);
        }
        $totalArticles = count($articlesData);
        $totalErrors = count($errors);
        $errorPercentage = ($totalErrors / $totalArticles) * 100;

        return [
            'errors' => $errors,
            'updated' => $updated,
            'error_percentage' => $errorPercentage
        ];
    }


    public function approveById($id, $qtstock)
    {
        return $this->articleRepository->approveById($id, $qtstock);
    }


    // public function searchByLibelleOrCategory()
    // {
    //     $request=request();
    //     return $this->articleRepository->filter($request);
    // }

}
