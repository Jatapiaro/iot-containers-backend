<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Container;
use App\Models\Measure;
use Carbon\Carbon;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Resources\Stat as StatResource;
use Auth;
use DB;

/**
 * @OA\Tag(
 *     name="Stats",
 *     description="Operations related to the stats",
 * )
 */
class StatController extends BaseController {

    public function day(Container $container)
    {
        $date = Carbon::now();
        $stOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();
        $meas = Measure::selectRaw('AVG(volume) average, HOUR(created_at) hour')
            ->where('created_at', '>=', $stOfDay)
            ->where('created_at', '<=', $endOfDay)
            ->groupBy('hour')
            ->get();
        return StatResource::collection($meas);
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
