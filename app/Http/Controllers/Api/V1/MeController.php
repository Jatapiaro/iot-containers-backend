<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Resources\User as UserResource;
use Auth;

/**
 * @OA\Tag(
 *     name="Profile",
 *     description="Operations related to the user profile",
 * )
 */
class MeController extends BaseController {

    /**
    * @OA\Get(
    *     path="/api/v1/me",
    *     summary="Shows the current user profile",
    *     tags={"Profile"},
    *     security={{"passport": {"*"}}},
    *     @OA\Response(
    *         response=200,
    *         description="Shows the user profile",
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
