<?php

namespace App\Services\Interfaces;

interface UserService{

    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(array $data);

    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function create(array $data);

    /**
     * @param array $data
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function update(array $data, $id);

    /**
     * @param $id
     * @return bool|int
     */
    public function delete($id);
}
