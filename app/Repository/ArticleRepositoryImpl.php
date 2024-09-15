<?php

namespace App\Repository;

use App\Models\Article;
use Auth;

class ArticleRepositoryImpl implements ArticleRepository
{
    protected $model;

    public function __construct(Article $model)
        {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        $user = Auth::user();
        $article=$this->model->create($data);
        $user->article()->save($article);
        return $article;
    }

    public function update(int $id, array $data)
    {
        $article = $this->model->find($id);
        return $article ? $article->update($data) : null;
    }

    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }
    public function approveById($id, $qtstock)
    {
        $article = Article::find($id);
        if ($article) {
            $article->qtstock += $qtstock;
            $article->save();
        }
        return $article;
    }
   

    public function filter($request)
    {
        $query = $this->model->newQuery();
        
        if ($request->missing('all')) {
            $query->ownedBy(auth()->id());
        }

        if ($request->has('disponible')) {
            $query->disponible($request->input('disponible'));
        }

        if ($request->has('labell')) {
            $query->byLabel($request->input('labell'));
        }

        if ($request->has('categorys')) {
            $query->byCategory($request->input('categorys'));
        }

        return $query->paginate(10);
    }
    public function updateAll($updatedArticles)
    {
        foreach ($updatedArticles as $updateData) {
            $this->update($updateData['id'],["qtstock"=>$updateData['qtstock']]);
            // Article::where('id', $updateData['id'])->update(['qtstock' => $updateData['qtstock']]);
        }
    }

}
