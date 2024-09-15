<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\PathItem(
 *     path="/api/v1/articles",
 *     @OA\Get(
 *         summary="Get all articles",
 *         description="Retrieve a list of all articles.",
 *         @OA\Response(
 *             response=200,
 *             description="A list of articles",
 *             @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Article"))
 *         )
 *     )
 * )
 */
/**
 * @OA\Schema(
 *     schema="ArticleResource",
 *     type="object",
 *     required={"id", "label", "description", "prix", "qtstock", "category"},
 *     @OA\Property(property="id", type="integer", format="int32"),
 *     @OA\Property(property="label", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="prix", type="number", format="float"),
 *     @OA\Property(property="qtstock", type="integer", format="int32"),
 *     @OA\Property(property="category", type="string")
 * )
 */

class SwaggerPaths
{
}
