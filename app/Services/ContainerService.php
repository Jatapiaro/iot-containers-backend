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
     * Updates the given container
     *
     * @param arr $data to update
     * @param Container $item to be updated
     *
     * @return App\Models\Container
     */
    public function update($data, Container $item) {
        /**
         * Customize validation book
         */
        $vb = Container::ValidationBook(['container.user_id']);
        $vb['rules']['container.device_id'] .= ",NULL,id,device_id,!{$item->device_id}";
        $this->validate($data, [], [], $vb);

        $data = $data["container"];
        $data["volume"] = $this->calculateVolume($data);

        // Update the container
        $this->repo->update($data, $item->id);
        $item->refresh();
        return $item;
    }

    /**
     * Validate the given data using the validation book of the model
     *
     * @param array $data
     * @param array $except
     * @param array $append
     * @return boolean
     */
    public function validate($data, $except = [], $append = [], $validationBook = null) {
        $vb = Container::ValidationBook($except, $append);
        if ($validationBook !== null) {
            $vb = $validationBook;
        }
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
