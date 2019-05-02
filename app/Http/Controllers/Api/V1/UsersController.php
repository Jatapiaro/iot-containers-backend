<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Resources\User as UserResource;

class UsersController extends BaseController {

    /**
     * Display user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        $user = Auth::user();
        return new UserResource($user);
    }

}
