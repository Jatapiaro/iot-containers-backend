<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Container;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Resources\Container as ContainerResource;
use Auth;

// Repos
use App\Repositories\Interfaces\ContainerRepoInterface;

// Services
use App\Services\ContainerService;

/**
 * @OA\Tag(
 *     name="Containers",
 *     description="Operations related to the containers",
 * )
 */
class ContainerController extends BaseController {

    /**
     * Container repo
     *
     * @var App\Repositories\Interfaces\ContainerRepoInterface;
     */
    private $containerRepo;

    /**
     * Container servicce
     *
     * @var App\Services\ContainerService
     */
    private $containerService;

    /**
     * Constructor
     */
    public function __construct(ContainerRepoInterface $containerRepo, ContainerService $containerService) {
        $this->containerRepo = $containerRepo;
        $this->containerService = $containerService;
    }

    /**
    * @OA\Get(
    *     path="/api/v1/containers",
    *     summary="Shows the containers of the current user",
    *     tags={"Containers"},
    *     security={{"passport": {"*"}}},
    *     @OA\Response(
    *         response=200,
    *         description="Shows the user containers",
    *         @OA\JsonContent(
    *             type="object"
    *         ),
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized.",
    *         @OA\JsonContent(
    *             type="object"
    *         ),
    *     )
    * )
    */
    /**
     * Display the user containers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $containers = Auth::user()->containers;
        return ContainerResource::collection($containers);
    }

}
