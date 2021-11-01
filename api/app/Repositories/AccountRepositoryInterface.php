<?php

namespace App\Repositories;

interface AccountRepositoryInterface
{
    public function get($id);
    public function save(Array $array);
}
