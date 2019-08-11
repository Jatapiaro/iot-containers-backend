<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use Auth;

/**
 * Models
 */
use App\Models\Container;

/**
 * Repos
 */
use App\Repositories\Interfaces\MeasureRepoInterface;

/**
 * Resources
 */
use App\Http\Resources\Stat as StatResource;

/**
 * @OA\Tag(
 *     name="Stats",
 *     description="Operations related to the stats",
 * )
 */
class StatController extends BaseController {

    /**
     * Measure repo
     *
     * @var App\Repositories\Interfaces\MeasureRepoInterface;
     */
    private $measureRepo;

    /**
     * Constructor
     */
    public function __construct(MeasureRepoInterface $measureRepo) {
        $this->measureRepo = $measureRepo;
    }

    /**
    * @OA\Get(
    *     path="/api/v1/stats/{container}/day",
    *     summary="Shows the volume average for each hour of the current day for a given container",
    *     tags={"Stats"},
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
    *         description="Shows the volume average for each hour of the current day",
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
     * Get the volume average for each hour of the current day for a given container
     * @param \Illuminate\Http\Request $request
     * @param App\Models\Container $container
     *
     * @return \Illuminate\Http\Response
     */
    public function day(Container $container)
    {
        $this->validateOwnerShip($container);
        $dayAverage = $this->measureRepo->dayAverage($container);
        return StatResource::collection($dayAverage);
    }


     /**
    * @OA\Get(
    *     path="/api/v1/stats/{container}/week",
    *     summary="Shows the volume average for each day of the current week for a given container",
    *     tags={"Stats"},
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
    *         description="Shows the volume average for each day of the current week",
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
     * Get the volume average for each day of the current week for a given container
     * @param \Illuminate\Http\Request $request
     * @param App\Models\Container $container
     *
     * @return \Illuminate\Http\Response
     */
    public function week(Container $container)
    {
        $this->validateOwnerShip($container);
        $weekAverage = $this->measureRepo->weekAverage($container);
        return StatResource::collection($weekAverage);
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
