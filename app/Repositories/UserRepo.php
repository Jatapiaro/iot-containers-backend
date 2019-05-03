<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Repositories\Interfaces\UserRepoInterface;
use App\Repositories\BaseEloquentRepo;

use App\Models\User;

class UserRepo extends BaseEloquentRepo implements UserRepoInterface
{
    public function __construct(User $entity) {
        $this->model = $entity;
    }

}
