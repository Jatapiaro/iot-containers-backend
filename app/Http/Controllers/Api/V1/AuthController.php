<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Models\Passport\Client;
use Auth;
use Hash;

// Resources
use App\Http\Resources\User as UserResource;

// Repos
use App\Repositories\Interfaces\UserRepoInterface;

// Services
use App\Services\UserService;


class AuthController extends BaseController {

    /**
     * User repo
     *
     * @var App\Repositories\Interfaces\UserRepoInterface
     */
    private $userRepo;

    /**
     * User servicce
     *
     * @var App\Services\UserService
     */
    private $userService;

    /**
     * Constructor
     */
    public function __construct(UserRepoInterface $repo, UserService $service) {
        $this->repo = $repo;
        $this->service = $service;
    }

    /**
     * Register a new user in the system.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $vb = User::ValidationBook();
        $data = $request->validate($vb["rules"], $vb["messages"]);

        // If validation passes, create user
        $password = $data["password"];
        $data["password"] = Hash::make($data["password"]);

        $user = $this->repo->create($data);

        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $data["client_id"],
            'client_secret' => $data["client_secret"],
            'username'      => $user->email,
            'password'      => $password,
            'scope'         => null,
        ]);

        // Fire off the internal request.
        $token = Request::create(
            'oauth/token',
            'POST'
        );
        return \Route::dispatch($token);

    }

}
