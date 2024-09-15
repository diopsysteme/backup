<?php

namespace App\Providers;
use App\Models\User;
// use Illuminate\Support\Facades\Gate;
use App\Models\Client;
use App\Enums\RoleEnum;
use App\Models\Article;
use App\Policies\UserPolicy;
use App\Policies\ClientPolicy;
use Laravel\Passport\Passport;
use App\Policies\ArticlePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Article::class => ArticlePolicy::class,
        User::class => UserPolicy::class,
        Client::class => ClientPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
        Gate::define('admin', [UserPolicy::class, 'viewAny']);
        Gate::define('client', [ClientPolicy::class, 'viewAny']);
        Gate::define('update-client', [ClientPolicy::class, 'view']);
        Gate::define('ableToAccess', [UserPolicy::class, 'view']);
        Gate::define('onlyclient', [ClientPolicy::class, 'onlyClient']);
        

        Gate::define('viewAny-client', function (User $user) {
            return $user->roleValue() == RoleEnum::BOUTIQUE->value;
        });
        Gate::define('demande',[ClientPolicy::class,'CanDemande'] ) ;
    }
}
