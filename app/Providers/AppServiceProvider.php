<?php

namespace App\Providers;
use App\Services\QrService;
use App\Services\PdfService;
use App\Services\SmsService;
use App\Services\PdfService2;
use App\Services\UserService;
use App\Services\DetteService;
use App\Services\ClientService;
use App\Services\MailerService;
use App\Services\ArticleService;
use App\Services\DemandeService;
use App\Services\PaymentService;
use Symfony\Component\Yaml\Yaml;
use App\Repository\UserRepository;
use App\Repository\DetteRepository;
use App\Services\FileUploadService;
use App\Exceptions\ServiceException;
use App\Repository\ClientRepository;
use App\Services\ArticleServiceImpl;
use App\Repository\ArticleRepository;
use App\Repository\DemandeRepository;
use App\Repository\PaymentRepository;
use App\Services\AuthenticateSanctum;
use App\Services\SmsServiceInterface;
use App\Services\AuthenticatePassport;
use App\Services\UserServiceInterface;
use App\Exceptions\RepositoryException;
use App\Services\DetteServiceInterface;
use App\Services\MongoDBArchiveService;
use Illuminate\Support\ServiceProvider;
use App\Services\ClientServiceInterface;
use App\Services\FirebaseArchiveService;
use App\Repository\ArticleRepositoryImpl;
use App\Services\DemandeServiceInterface;
use App\Services\PaymentServiceInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\DetteRepositoryInterface;
use App\Repository\ClientRepositoryInterface;
use App\Services\CloudinaryFileUploadService;
use App\Providers\AuthenticateServiceProvider;
use App\Repository\DemandeRepositoryInterface;
use App\Repository\PaymentRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthenticateServiceProvider::class, function ($app) {
            if (config('auth.default') === 'passport') {
                return new AuthenticatePassport();
            } else {
                return new AuthenticateSanctum();
            }
        });
        $this->app->bind(ArticleRepository::class, ArticleRepositoryImpl::class);
        $this->app->bind(ArticleService::class, ArticleServiceImpl::class);
        $this->app->bind(DemandeServiceInterface::class, DemandeService::class);
        $this->app->bind(DemandeRepositoryInterface::class, DemandeRepository::class);

        
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(ClientServiceInterface::class, ClientService::class);
        $this->app->singleton(DetteRepositoryInterface::class, DetteRepository::class);
        $this->app->singleton(DetteServiceInterface::class, DetteService::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);

        // Lier l'interface du service à l'implémentation
        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        
        $config = Yaml::parseFile(config_path('services.yaml'));

        foreach ($config['services']['bindings'] as $serviceConfig) {
            $interface = $serviceConfig['interface'];
            $defaultClass = $serviceConfig['default'];
            $alternativeClass = $serviceConfig['alternative'] ?? null;
            $key = $serviceConfig['key'] ?? null;
            $use = $serviceConfig['use'] ?? 'default';
            $serviceConfigExtra = $serviceConfig['config'] ?? [];
    
            $serviceClass = ($use === 'alternative' && $alternativeClass) ? $alternativeClass : $defaultClass;
    
            $this->app->bind($interface, function ($app) use ($serviceClass, $serviceConfigExtra) {
                // if (empty($serviceConfigExtra)) {
                    return new $serviceClass();
                // }
    
                // $reflector = new \ReflectionClass($serviceClass);
                // $constructor = $reflector->getConstructor();
    
                // if ($constructor) {
                //     $parameters = [];
                //     foreach ($constructor->getParameters() as $param) {
                //         $paramName = $param->getName();
                //         if (array_key_exists($paramName, $serviceConfigExtra)) {
                //             $parameters[] = $serviceConfigExtra[$paramName];
                //         } elseif ($param->isDefaultValueAvailable()) {
                //             $parameters[] = $param->getDefaultValue();
                //         } elseif ($param->allowsNull()) {
                //             $parameters[] = null;
                //         } else {
                //             throw new \InvalidArgumentException("Missing required parameter: $paramName for service ");
                //         }
                //     }
                //     return $reflector->newInstanceArgs($parameters);
                // }
    
                // return new $serviceClass();
            });
    
            if ($key) {
                $this->app->singleton($key, function ($app) use ($interface) {
                    return $app->make($interface);
                });
            }
        }
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
            
    }
}
