<?php
/**
 * @OA\Tag(
 *     name="Articles",
 *     description="Gestion des articles"
 * )
 */

/**
 * @OA\Get(
 *     path="/api/articles",
 *     tags={"Articles"},
 *     summary="Lister les articles",
 *     description="Récupère une liste des articles. Filtre les résultats selon les paramètres fournis.",
 *     @OA\Parameter(
 *         name="all",
 *         in="query",
 *         description="Si défini, récupère tous les articles.",
 *         required=false,
 *         @OA\Schema(type="boolean")
 *     ),
 *     @OA\Parameter(
 *         name="disponible",
 *         in="query",
 *         description="Filtrer selon la disponibilité ('oui' ou 'non').",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Liste des articles récupérée avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(ref="#/components/schemas/Article")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=403, description="Accès refusé")
 * )
 */

/**
 * @OA\Post(
 *     path="/api/articles",
 *     tags={"Articles"},
 *     summary="Créer un nouvel article",
 *     description="Crée un article avec les données fournies.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/StoreArticleRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Article créé avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", ref="#/components/schemas/Article")
 *         )
 *     ),
 *     @OA\Response(response=400, description="Validation échouée"),
 *     @OA\Response(response=403, description="Accès refusé")
 * )
 */

/**
 * @OA\Get(
 *     path="/api/articles/{id}",
 *     tags={"Articles"},
 *     summary="Afficher un article",
 *     description="Récupère les détails d'un article spécifique par son ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'article",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Détails de l'article récupérés avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", ref="#/components/schemas/Article")
 *         )
 *     ),
 *     @OA\Response(response=404, description="Article non trouvé")
 * )
 */

/**
 * @OA\Put(
 *     path="/api/articles/{id}",
 *     tags={"Articles"},
 *     summary="Mettre à jour un article",
 *     description="Met à jour les informations d'un article par son ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'article",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/UpdateArticleRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Article mis à jour avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", ref="#/components/schemas/Article")
 *         )
 *     ),
 *     @OA\Response(response=404, description="Article non trouvé")
 * )
 */

/**
 * @OA\Delete(
 *     path="/api/articles/{id}",
 *     tags={"Articles"},
 *     summary="Supprimer un article",
 *     description="Supprime un article par son ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'article",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Article supprimé avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *         )
 *     ),
 *     @OA\Response(response=404, description="Article non trouvé")
 * )
 */

/**
 * @OA\Post(
 *     path="/api/articles/approve",
 *     tags={"Articles"},
 *     summary="Approuver des articles",
 *     description="Approuve des articles en fonction de l'ID et de la quantité fournis.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/AproveArticleRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Articles approuvés avec quelques erreurs",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="errors", type="array",
 *                     @OA\Items(type="object")
 *                 ),
 *                 @OA\Property(property="updated", type="array",
 *                     @OA\Items(type="object")
 *                 ),
 *                 @OA\Property(property="error_percentage", type="number")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=400, description="Validation échouée")
 * )
 */

/**
 * @OA\Patch(
 *     path="/api/articles/{id}",
 *     tags={"Articles"},
 *     summary="Approuver un article par ID",
 *     description="Augmente la quantité en stock d'un article spécifique.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'article",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/ApproveByIdArticleRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Article approuvé avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", ref="#/components/schemas/Article")
 *         )
 *     ),
 *     @OA\Response(response=404, description="Article non trouvé")
 * )
 */

/**
 * @OA\Post(
 *     path="/api/articles/libelle",
 *     tags={"Articles"},
 *     summary="Rechercher un article par libellé ou catégorie",
 *     description="Recherche un article par son libellé ou sa catégorie.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/GetArtticleByLibelleRequest")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Article trouvé",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(ref="#/components/schemas/Article")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=404, description="Article non trouvé")
 * )
 */

/**
 * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     required={"label", "description", "prix", "qtstock", "category"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="label", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="prix", type="number", format="float"),
 *     @OA\Property(property="qtstock", type="integer"),
 *     @OA\Property(property="category", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

