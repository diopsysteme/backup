<?php

namespace App\Http\Controllers;

use App\Models\Dette;
use App\Models\Article;
use App\Enums\StatutEnum;
use App\Enums\EtatDetteEnum;
use Illuminate\Http\Request;
use App\Services\DetteService;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\DetteResource;
use App\Services\DetteServiceInterface;
use App\Http\Requests\StoreDetteRequest;

class DetteController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */

     protected $detteService;

    public function __construct(DetteServiceInterface $detteService)
    {
        $this->detteService = $detteService;
    }
    public function index()
    {
       return DetteResource::collection($this->detteService->getAllDettes());

        
    }

    public function store(StoreDetteRequest $request)
    {
        $resultat = $this->detteService->verifierEtEnregistrerDette($request);

        return $resultat['statut'] == 'echec' ?
        ['statut' => "KO",'message' => $resultat['message'] ?? '','code' => 400]
        :[$resultat,'code' => 201];
    }

    
    public function show($id)
    {
        return new DetteResource($this->detteService->recupererDette($id,["client"]));
    }
    public function showArticle($id)
    {
        return new DetteResource($this->detteService->recupererDette($id,["articles"]));
    }
    public function showPayement($id)
    {
        return new DetteResource($this->detteService->recupererDette($id,["payement"]));
    }



    
 

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dette $dette)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dette $dette)
    {
        //
    }
}
