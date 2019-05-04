<?php
namespace App\Services;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;

use App\Models\User;

// Repos
use App\Repositories\Interfaces\UserRepoInterface;

class UserService {

    /**
     * Repository
     *
     * @var UserRepoInteface
     */
    private $repo;

    public function __construct(UserRepoInterface $repo) {
        $this->repo = $repo;
    }

    /**
     * Stores the given user
     *
     * @return App\Models\User
     */
    public function store($data) {
        $this->validate($data, ["user.client_id", "user.client_secret"]);
        // We extract only the user data
        $data = $data["user"];
        // Hash the password
        $data["password"] = Hash::make($data["password"]);
        // Store the user
        $user = $this->repo->create($data);
        return $user;
    }

    /**
     * Validate the given data using the validation book of the model
     *
     * @param array $data
     * @param array $except
     * @param array $append
     * @return boolean
     */
    public function validate($data, $except = [], $append = []) {
        $vb = User::ValidationBook($except, $append);
        $validator = Validator::make($data, $vb['rules'], $vb['messages']);
        if ($validator->fails()) {
            $errors = $validator->errors();
            throw ValidationException::withMessages($errors->toArray());
        }
        return true;
    }
}
