<?php
namespace App\Repositories\Interfaces;

interface RepoInterface {
    /**
     * Returns all the items on this repo
     *
     * @return collection
     */
    public function all();

    /**
     * Creates the model with the given $data
     *
     * @param array $data
     * @return StdClass
     */
    public function create(array $data);

    /**
     * Updates the model with the $id with the given $data
     *
     * @param array $data
     * @param integer $id
     * @return StdClass
     */
    public function update(array $data, $id);

    /**
     * Deletes the model with the $id
     *
     * @param integer $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Returns the model with the given $id
     *
     * @param integer $id
     * @return StdClass
     */
    public function find($id);

    /**
     * Get paged items
     *
     * @param  integer $paged Items per page
     * @param  string $orderBy Column to sort by
     * @param  string $sort Sort direction
     */
    public function paginated($paged = 15, $orderBy = 'id', $sort = 'asc');
}
