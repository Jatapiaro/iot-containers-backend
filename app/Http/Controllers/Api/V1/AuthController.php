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

/**
 * @OA\Tag(
 *     name="User registration",
 *     description="Operations for the user registration",
 * )
 */
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
    public function __construct(UserRepoInterface $userRepo, UserService $userService) {
        $this->userRepo = $userRepo;
        $this->userService = $userService;
    }

    /**
    * @OA\Post(
    *     path="/api/v1/register",
    *     summary="Register a new user",
    *     tags={"Profile"},
    *     security={{"passport": {"*"}}},
    *     @OA\RequestBody(
    *         description="User to be registered",
    *         @OA\JsonContent(
    *              @OA\Property(
    *                  property="user",
    *                  type="object",
    *                  ref="#/components/schemas/User"
    *              ),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Data with token_type, exprires_at and the acces and refresh token",
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
     * Register a new user in the system.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $vb = User::ValidationBook();
        $data = $request->validate($vb["rules"], $vb["messages"]);

        // Neccesary data to get a token at registration
        $password = $data["user"]["password"];
        $clientId = $data["user"]["client_id"];
        $clientSecret = $data["user"]["client_secret"];

        // If validation passes, create user
        $user = $this->userService->store($data);

        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
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
