<?php
namespace App\Repository;

use App\Exceptions\RepositoryException;
use App\Exceptions\ServiceException;
use App\Models\Dette;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

class DetteRepository implements DetteRepositoryInterface
{
    protected $model;
    protected $request;

    public function __construct(Dette $dette)
    {
        $this->model = $dette;
        $this->request = request();

    }
    public function createDette($request, $montant_total)
    {
        try {
            $dette = Dette::create([
                'client_id' => $request->client_id,
                'date' => now(),
                'montant' => $montant_total,
                'etat_solde' => false,
                'date_echeance' => now()->addDays(env('LIMITEDAY',15)),
                'user_id' => auth()->id(),
            ]);

            return [
                'statut' => 'succes',
                'message' => 'Dette enregistrée avec succès.',
                'dette' => $dette->load('articles')
            ];
        } catch (ServiceException $e) {
            return [
                'statut' => 'echec',
                'message' => 'Une erreur est survenue lors de l\'enregistrement de la dette : ' . $e->getMessage()
            ];
        }
    }
    public function save(array $data)
    {
        return $this->model->create($data);
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }
    public function getAll()
    {
        $dettes = Dette::all();

        if ($this->request->has('statut')) {
            $statut=$this->request->get('statut');
            if ($statut=="solde") {
                $dettes = $dettes->filter(function ($dette) {
                    return $dette->etat_solde;
                });
            } else {
                $dettes = $dettes->filter(function ($dette) {
                    return !$dette->etat_solde;
                });
            }
        }

        return $dettes;
    }
    public function getAll2()
    {
        $query = Dette::query();
        if ($this->request->has('solde')) {
            $query->whereHas('payement', function ($q) {
                $q->havingRaw('SUM(montant) >= dettes.montant');
            });
        } elseif ($this->request->has('non_solde')) {
            $query->whereHas('payement', function ($q) {
                $q->havingRaw('SUM(montant) < dettes.montant');
            });
        }
        return $query->with('payement')->get();
    }
    public function recupererDette($id, array $relations = [])
    {
        try {
            // dd(Dette::with($relations)->findOrFail($id));
            return Dette::with($relations)->findOrFail($id);
        } catch (RepositoryException $th) {
            throw new RepositoryException('Failed to upload file: ' . "dddd", 'FileUploadService');
        }
        
    }

}
