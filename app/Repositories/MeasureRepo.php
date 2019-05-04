<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Repositories\Interfaces\MeasureRepoInterface;
use App\Repositories\BaseEloquentRepo;

use App\Models\Measure;

class MeasureRepo extends BaseEloquentRepo implements MeasureRepoInterface
{
    public function __construct(Measure $entity) {
        $this->model = $entity;
    }

}
