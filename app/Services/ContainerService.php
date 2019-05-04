<?php
namespace App\Services;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Validator;

use App\Models\Container;

// Repos
use App\Repositories\Interfaces\ContainerRepoInterface;

class ContainerService {

    /**
     * Repository
     *
     * @var ContainerRepoInteface
     */
    private $repo;

    public function __construct(ContainerRepoInterface $repo) {
        $this->repo = $repo;
    }

    /**
     * Stores the given container
     *
     * @return App\Models\Container
     */
    public function store($data) {
        $this->validate($data);
        $data = $data["container"];
        $data["volume"] = $this->calculateVolume($data);
        // Store the container
        $container = $this->repo->create($data);
        return $container;
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
        $vb = Container::ValidationBook($except, $append);
        $validator = Validator::make($data, $vb['rules'], $vb['messages']);
        if ($validator->fails()) {
            $errors = $validator->errors();
            throw ValidationException::withMessages($errors->toArray());
        }
        return true;
    }


    /**
     * Return the volume of a container in m3
     * @param $data of the container
     * @return float with the volume
     */
    private function calculateVolume($data) {
        $radius = $data["radius"];
        $height = $data["height"];
        return pi() * ($radius * $radius) * $height;
    }

}
