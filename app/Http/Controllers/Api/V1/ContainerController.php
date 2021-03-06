<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Container;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    * @OA\Get(
    *     path="/api/v1/containers/{container}",
    *     summary="Shows the specified container",
    *     tags={"Containers"},
    *     security={{"passport": {"*"}}},
    *     @OA\Parameter(
    *         name="container",
    *         in="path",
    *         description="ID of the container",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64",
    *             example=1
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Shows the specified container",
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
     * Get the specified element
     * @param \Illuminate\Http\Request $request
     * @param App\Models\Container $container
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Container $container) {
        $this->validateOwnerShip($container);
        return new ContainerResource($container);
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
        $this->validateOwnerShip($container);
        $vb = Container::ValidationBook(['container.user_id']);
        $vb['rules']['container.device_id'] .= ",NULL,id,device_id,!{$container->device_id}";
        $data = $request->validate($vb["rules"], $vb["messages"]);
        /**
         * In case the device_id is not being send
         * we force the value to null
         */
        if (!isset($data['container']['device_id'])) {
            $data['container']['device_id'] = null;
        }
        $container = $this->containerService->update($data, $container);
        return new ContainerResource($container);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/containers/{container}",
     *     summary="Deletes a container",
     *     tags={"Containers"},
     *     security={{"passport": {"*"}}},
     *     @OA\Parameter(
     *         description="Container to be deleted",
     *         in="path",
     *         name="container",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Container that was deleted",
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
    public function destroy(Request $request, Container $container) {
        $this->validateOwnerShip($container);
        $container->delete();
        return new ContainerResource($container);
    }

    /**
     * Validates if the current user is the owner of the container
     *
     * @param App\Models\Container $container
     */
    private function validateOwnerShip(Container $container) {
        $user = Auth::user();
        if ($user->id !== $container->user_id) {
            throw new ModelNotFoundException("The element with the {$container->id} was not found");
        }
    }

}
