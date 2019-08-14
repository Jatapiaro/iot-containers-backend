<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Measure;
use App\Models\Container;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Resources\Measure as MeasureResource;
use Auth;

// Repos
use App\Repositories\Interfaces\MeasureRepoInterface;

// Services
use App\Services\MeasureService;

/**
 * @OA\Tag(
 *     name="Measures",
 *     description="Operations related to the measure of water in a container",
 * )
 */
class MeasureController extends BaseController {

    /**
     * Measure repo
     *
     * @var App\Repositories\Interfaces\MeasureRepoInterface;
     */
    private $measureRepo;

    /**
     * Measure servicce
     *
     * @var App\Services\MeasureService
     */
    private $measureService;

    /**
     * Constructor
     */
    public function __construct(MeasureRepoInterface $measureRepo, MeasureService $measureService) {
        $this->measureRepo = $measureRepo;
        $this->measureService = $measureService;
    }

    /**
    * @OA\Get(
    *     path="/api/v1/containers/{container}/measures",
    *     summary="Shows the measures of the given container",
    *     tags={"Measures"},
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
    *         description="Shows the container measures",
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Container $container
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Container $container)
    {
        $measures = ($container->user_id == Auth::user()->id)? $container->measures : Measure::where('id', '-1')->get();
        return MeasureResource::collection($measures->take(60));
    }

    /**
    * @OA\Post(
    *     path="/api/v1/containers/{container}/measures",
    *     summary="Register a new measure",
    *     tags={"Measures"},
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
    *     @OA\RequestBody(
    *         description="Measure to be registered",
    *         @OA\JsonContent(
    *              @OA\Property(
    *                  property="measure",
    *                  type="object",
    *                  ref="#/components/schemas/Measure"
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
     * Register a new measure in the system.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Container $container
     */
    public function store(Request $request, Container $container) {
        if ($container->user_id != Auth::user()->id) {
            return new MeasureResource(null);
        }
        $vb = Measure::ValidationBook(['measure.container_id']);
        $data = $request->validate($vb["rules"], $vb["messages"]);
        // Add the container id to the measure
        $data["measure"]["container_id"] = $container->id;
        $pi = pi();
        $r2 = $container->radius * $container->radius;
        $measureVolume = $pi * $r2 * $data["measure"]["height"];
        $data["measure"]["volume"] = ($container->volume - $measureVolume);
        $measure = $this->measureService->store($data);
        return new MeasureResource($measure);
    }

    /**
    * @OA\Post(
    *     path="/api/v1/particle/{device}",
    *     summary="Register a new measure from a photon particle",
    *     tags={"Measures"},
    *     security={{"passport": {"*"}}},
    *     @OA\Parameter(
    *         name="device",
    *         in="path",
    *         description="ID of the device",
    *         required=true,
    *         @OA\Schema(
    *             type="string",
    *             example="123456789"
    *         )
    *     ),
    *     @OA\RequestBody(
    *         description="Measure to be registered",
    *         @OA\JsonContent(
    *              @OA\Property(
    *                  property="measure",
    *                  type="object",
    *                  ref="#/components/schemas/Measure"
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
     * Stores a measure using particle.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $device id
     * @return \Illuminate\Http\Response
     */
    public function particle(Request $request, $device) {
        $container = Container::where('device_id', $device)->first();
        if (is_null($container)) {
            return new MeasureResource(null);
        }

        $vb = Measure::ValidationBook(['measure.container_id']);
        $data = $request->validate($vb["rules"], $vb["messages"]);

        $data["measure"]["container_id"] = $container->id;

        // Calculate current volume
        $pi = pi();
        $r2 = $container->radius * $container->radius;
        $measureVolume = $pi * $r2 * $data["measure"]["height"];
        $data["measure"]["volume"] = ($container->volume - $measureVolume);

        $measure = $this->measureService->store($data);
        return new MeasureResource($measure);
    }

}
