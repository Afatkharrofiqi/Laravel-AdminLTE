<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Abstracts\UserRepository;

class UserRepositoryImpl extends UserRepository
{
    public function model()
    {
        return User::class;
    }

    public function filterData(array $filter, $query)
    {

    }
}
