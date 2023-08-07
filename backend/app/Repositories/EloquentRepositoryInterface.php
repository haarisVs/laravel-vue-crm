<?php

namespace App\Repositories;

interface EloquentRepositoryInterface
{
    public function create(array $attributes);

    public function update($id, array $attributes);

    public function destroy($id);

    public function findById($id);

    public function findAll(array $params);

}
