<?php
namespace App\Services;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Validator;

use App\Models\Measure;

// Repos
use App\Repositories\Interfaces\MeasureRepoInterface;

class MeasureService {

    /**
     * Repository
     *
     * @var MeasureRepoInteface
     */
    private $repo;

    public function __construct(MeasureRepoInterface $repo) {
        $this->repo = $repo;
    }

    /**
     * Stores the given measure
     *
     * @return App\Models\Measure
     */
    public function store($data) {
        $this->validate($data);
        $data = $data["measure"];
        // Store the container
        $measure = $this->repo->create($data);
        return $measure;
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
        $vb = Measure::ValidationBook($except, $append);
        $validator = Validator::make($data, $vb['rules'], $vb['messages']);
        if ($validator->fails()) {
            $errors = $validator->errors();
            throw ValidationException::withMessages($errors->toArray());
        }
        return true;
    }

}
