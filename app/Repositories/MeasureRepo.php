<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Repositories\Interfaces\MeasureRepoInterface;
use App\Repositories\BaseEloquentRepo;

use App\Models\Measure;
use App\Models\Container;
use Carbon\Carbon;
use DB;

class MeasureRepo extends BaseEloquentRepo implements MeasureRepoInterface
{
    public function __construct(Measure $entity) {
        $this->model = $entity;
    }

    /**
     * Obtains the volume average for each hour of the day
     *
     * @param App\Models\Container $container of the measures
     *
     * @return Collection
     */
    public function dayAverage(Container $container) {
        $date = Carbon::now();
        $stOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        $dayAverage = $this->model->selectRaw('AVG(volume) volume, HOUR(created_at) hour')
            ->where('created_at', '>=', $stOfDay)
            ->where('created_at', '<=', $endOfDay)
            ->where('container_id', $container->id)
            ->groupBy('hour')
            ->get();

        return $dayAverage;
    }

    /**
     * Obtains the volume average for each day of the week
     *
     * @param App\Models\Container $container of the measures
     *
     * @return Collection
     */
    public function weekAverage(Container $container) {
        $date = Carbon::now(); 
        $startDate = $date->copy()->startOfWeek();
        $endDate = $date->copy()->endOfWeek();

        $weekAverage = $this->model->selectRaw('AVG(volume) volume, DAYOFWEEK(created_at) as day')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->where('container_id', $container->id)
            ->groupBy('day')
            ->get();

        return $weekAverage;
    }

}
