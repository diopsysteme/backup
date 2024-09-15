<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Auth;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    /**
     * Determine whether the user can view any models.
     */
   /**
 * Determine whether the user can view any models.
 */

 public function update(User $user, Article $article)
 {
     return $user->id === $article->user_id
         ? Response::allow()
         : Response::deny('You do not own this post.');
 }
 

 public function viewAny(User $user)
 {
   
     if ($user->isAdmin()) {
        
         return Response::allow('Access granted');
     }
     return Response::deny('You do not have permission to view this resource', 403);
 }
 

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Article $article): bool
    {
        // Permettre à tous les utilisateurs de voir les articles
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Permettre à tous les utilisateurs authentifiés de créer des articles
        return $user->isAuthenticated(); // ou tout autre condition
    }

    /**
     * Determine whether the user can update the model.
     */
   

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Article $article): bool
    {
        // Permettre à l'utilisateur de supprimer uniquement ses propres articles
        return $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Article $article): bool
    {
        // Permettre à l'utilisateur de restaurer uniquement ses propres articles
        return $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Article $article): bool
    {
        // Permettre à l'utilisateur de supprimer définitivement uniquement ses propres articles
        return $user->id === $article->user_id;
    }
}
