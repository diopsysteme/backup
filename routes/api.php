<?php

use App\Http\Controllers\DemandeController;
use App\Http\Controllers\DetteArchiveController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DetteController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PaymentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
 Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
     return $request->user();
 });
 */
Route::get('/dettea', [DetteArchiveController::class, 'show']);

Route::get('/carte',function(){
   return view('carte');
});
Route::prefix('v1')->group(function () {

    // Auth routes
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('login', [AuthController::class, 'login']);

    // Authenticated routes
    Route::middleware('verify.token')->group(function () {
        
        // User routes
        Route::controller(UserController::class)->prefix('users')->group(function () {
            Route::post('/', 'store');
            Route::put('{id}', 'update');
            Route::patch('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'show');
            Route::post('client', 'storeUserSaveClient');
            Route::get('/', 'index');
        });
        Route::controller(PaymentController::class)->prefix('payments')->group(function () {
            Route::post('/', 'store');
            Route::put('{id}', 'update');
            Route::patch('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::get('{id}', 'show');
            Route::get('/', 'index');
        });
        Route::controller(DemandeController::class)->prefix('demandes')->group(function () {
            Route::post('/', 'store');
            // Route::put('{id}', 'update');
            // Route::patch('{id}', 'update');
            // Route::delete('{id}', 'destroy');
            // Route::get('{id}', 'show');
             Route::get('/notifications/client', 'notifDemande');
             Route::get('/notifications', 'getBoutiquierNotifications');
             Route::get('/all', 'getAllDemandesForBoutiquier');
            Route::post('/{id}/relance', 'relance');
            Route::get('', 'index');
        });
        
        // Dette routes
        Route::prefix('dettes')->middleware("can:viewAny-client")->group(function () {
            Route::get('/', [DetteController::class, 'index']);
            Route::get('{id}', [DetteController::class, 'show']);
            Route::get('{id}/articles', [DetteController::class, 'showArticle']);
            Route::get('{id}/payement', [DetteController::class, 'showPayement']);
            Route::post('/', [DetteController::class,'store']);
            Route::put('{id}', [DetteController::class, 'update']);
            Route::patch('{id}', [DetteController::class, 'update']);
            Route::delete('{id}', [DetteController::class, 'destroy']);
        });
        
        // Client routes

        Route::prefix('clients')->controller(ClientController::class)->group(function () {
           Route::middleware('can:viewAny-client')->group(function () {
            Route::get('/',  'index');
            Route::post('/',  'store');
            Route::post('telephone',  'filterByTelephone');
            Route::get('{id}/user',  'show');
           });
            Route::get('{id}',  'show');
            Route::put('{id}',  'update');
            Route::patch('{id}',  'update');
           Route::get('{clientId}/dettes',  'dettes');
        });
        
        // Article routes
        Route::prefix('articles')->middleware("can:viewAny-client")->group(function () {
            Route::get('/', [ArticleController::class, 'index']);
            Route::get('{id}', [ArticleController::class, 'show']);
            Route::post('/', [ArticleController::class, 'store']);
            Route::put('{id}', [ArticleController::class, 'update']);
            Route::post('approve', [ArticleController::class, 'approve']);
            Route::patch('{id}', [ArticleController::class, 'approveById']);
            Route::post('libelle', [ArticleController::class, 'byLibelleOrCategory']);
            Route::delete('{id}', [ArticleController::class, 'destroy']);
        });
    });
});



// Route::prefix('users')->group(function () {
//     Route::get('/', [UserController::class, 'index']);          // GET api/v1/users
//     Route::get('{id}', [UserController::class, 'show']);         // GET api/v1/users/{id}
//     Route::post('/', [UserController::class, 'store']);          // POST api/v1/users
//     Route::put('{id}', [UserController::class, 'update']);       // PUT api/v1/users/{id}
//     Route::patch('{id}', [UserController::class, 'update']);     // PATCH api/v1/users/{id}
//     Route::delete('{id}', [UserController::class, 'destroy']);   // DELETE api/v1/users/{id}
// });

// // Routes pour les articles
// Route::prefix('articles')->group(function () {
//     Route::get('/', [ArticleController::class, 'index']);          // GET api/v1/articles
//     Route::get('{id}', [ArticleController::class, 'show']);         // GET api/v1/articles/{id}
//     Route::post('/', [ArticleController::class, 'store']);          // POST api/v1/articles
//     Route::put('{id}', [ArticleController::class, 'update']);       // PUT api/v1/articles/{id}
//     Route::patch('{id}', [ArticleController::class, 'update']);     // PATCH api/v1/articles/{id}
//     Route::delete('{id}', [ArticleController::class, 'destroy']);   // DELETE api/v1/articles/{id}
// });

// // Routes pour les dettes
// Route::prefix('dettes')->group(function () {
//     Route::get('/', [DetteController::class, 'index']);          // GET api/v1/dettes
//     Route::get('{id}', [DetteController::class, 'show']);         // GET api/v1/dettes/{id}
//     Route::post('/', [DetteController::class, 'store']);          // POST api/v1/dettes
//     Route::put('{id}', [DetteController::class, 'update']);       // PUT api/v1/dettes/{id}
//     Route::patch('{id}', [DetteController::class, 'update']);     // PATCH api/v1/dettes/{id}
//     Route::delete('{id}', [DetteController::class, 'destroy']);   // DELETE api/v1/dettes/{id}
// });