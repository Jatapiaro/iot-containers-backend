<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Repositories\Interfaces\ContainerRepoInterface;
use App\Repositories\BaseEloquentRepo;

use App\Models\Container;

class ContainerRepo extends BaseEloquentRepo implements ContainerRepoInterface
{
    public function __construct(Container $entity) {
        $this->model = $entity;
    }

}
