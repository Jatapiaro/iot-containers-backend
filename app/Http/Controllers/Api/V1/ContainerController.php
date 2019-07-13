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

    /**
    * @OA\Post(
    *     path="/api/v1/containers",
    *     summary="Register a new container",
    *     tags={"Containers"},
    *     security={{"passport": {"*"}}},
    *     @OA\RequestBody(
    *         description="Container to be registered",
    *         @OA\JsonContent(
    *              @OA\Property(
    *                  property="container",
    *                  type="object",
    *                  ref="#/components/schemas/Container"
    *              ),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Container that was created",
    *         @OA\JsonContent(
    *             type="object"
    *         ),
    *     ),
    *     @OA\Response(
    *         response=422,
    *         description="Unprocessable Entity.",
    *         @OA\JsonContent(
    *             type="object"
    *         ),
    *     )
    * )
    */
    /**
     * Register a new container in the system.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $vb = Container::ValidationBook(['container.user_id']);
        $data = $request->validate($vb["rules"], $vb["messages"]);
        // Add the user id to the container
        $data["container"]["user_id"] = Auth::user()->id;
        $container = $this->containerService->store($data);
        return new ContainerResource($container);
    }

    /**
    * @OA\Put(
    *     path="/api/v1/containers/{container}",
    *     summary="Updates a container",
    *     tags={"Containers"},
    *     security={{"passport": {"*"}}},
    *     @OA\Parameter(
    *         description="Container to update",
    *         in="path",
    *         name="container",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\RequestBody(
    *         description="Data of the container to be updated",
    *         @OA\JsonContent(
    *              @OA\Property(
    *                  property="container",
    *                  type="object",
    *                  ref="#/components/schemas/Container"
    *              ),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Container that was updated",
    *         @OA\JsonContent(
    *             type="object"
    *         ),
    *     ),
    *     @OA\Response(
    *         response=422,
    *         description="Unprocessable Entity.",
    *         @OA\JsonContent(
    *             type="object"
    *         ),
    *     )
    * )
    */
    /**
     * Updates a Container from the system
     * @param \Illuminate\Http\Request $request
     * @param App\Models\Container $container
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Container $container) {
        // TODO: throw ModelNotFoundException if user is not the owner of the container
        $vb = Container::ValidationBook(['container.user_id']);
        $data = $request->validate($vb["rules"], $vb["messages"]);
        $container = $this->containerService->update($data, $container);
        return new ContainerResource($container);
    }

}
