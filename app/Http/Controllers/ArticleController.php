<?php

namespace App\Http\Controllers;

use App\Exceptions\ServiceException;
use App\Models\Article;
use App\Enums\StatutEnum;
use Illuminate\Http\Request;
use App\Services\ArticleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\ArticleRessource;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\AproveArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Requests\ApproveByIdArticleRequest;
use App\Http\Requests\GetArtticleByLibelleRequest;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    use ApiResponseTrait;
   
    public function index()
    {
        
        $articles = $this->articleService->getAllArticles();
        return ['statut' => StatutEnum::SUCCESS,'data' => $articles,'message' => 'Articles retrieved successfully'];
    }

    public function store(StoreArticleRequest $request)
    {
        $article = $this->articleService->createArticle($request->only(['label', 'description', 'prix', 'qtstock', 'category']));
        
        $articleRessource = new ArticleRessource($article);
        return ['statut' => StatutEnum::SUCCESS,'data' => $articleRessource,'message' => 'Article créé avec succès.'];
    }

    public function show(Request $request, $id)
    {
        $article = $this->articleService->findById($id);
    
        if (!$article) {
            return ['statut' => StatutEnum::ECHEC,'data' => null,'message' => 'Article non trouvé.'];
        }
    
        $articleResource = new ArticleRessource($article);
        return ['statut' => StatutEnum::SUCCESS,'data' => $articleResource,'message' => 'Article récupéré avec succès.'];
    }

    public function update(UpdateArticleRequest $request, $id)
    {
        try {
            $updatedArticle = $this->articleService->updateArticle($id, $request->validated());

            return ['statut' => StatutEnum::SUCCESS, 'data' => $updatedArticle, 'message' => 'Article mis à jour avec succès'];
        } catch (ServiceException $e) {
            return ['statut' => StatutEnum::ECHEC, 'data' => null,'message' => 'Article non trouvé'];
        }
    }

    public function approve(AproveArticleRequest $request)
    {
        $result = $this->articleService->approveArticles($request->input('article'));

        return [
            'statut' => StatutEnum::SUCCESS,
            'data' => ['errors' => $result['errors'],'updated' => $result['updated'],'error_percentage' => $result['error_percentage']],
            'message' => !empty($result['errors'])?'Approbation terminée avec erreur':'Approbation terminée'
        ];
    }

    public function approveById(ApproveByIdArticleRequest $request, $id)
    {
        $result = $this->articleService->approveById($id, $request->qtstock);

        if ($result) {
            return ['statut' => StatutEnum::SUCCESS,'data' => $result,'message' => 'Article approuvé avec succès'];
        } else {
            return ['statut' => StatutEnum::ECHEC,'data' => null,'message' => 'Article non trouvé'];
        }
    }

    public function byLibelleOrCategory(GetArtticleByLibelleRequest $request)
    {
        $articles = $this->articleService->getAllArticles();

        if ($articles->isEmpty()) {
            return ['statut' => StatutEnum::ECHEC,'data' => null,'message' => 'Aucun critère de recherche fourni.'];
        }
        return ['statut' => StatutEnum::SUCCESS,'data' => $articles,'message' => 'Articles trouvés'];
    }
   
   
    
}
