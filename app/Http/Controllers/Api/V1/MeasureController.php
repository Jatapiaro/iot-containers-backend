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
    *     tags={"Containers", "Measures"},
    *     security={{"passport": {"*"}}},
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
        $measures = $container->measures;
        return MeasureResource::collection($measures);
    }
}
