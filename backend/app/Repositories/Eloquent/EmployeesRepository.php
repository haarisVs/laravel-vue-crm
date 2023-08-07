<?php

namespace App\Repositories\Eloquent;

use App\Models\Company;
use App\Models\Employee;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EmployeesRepository implements EloquentRepositoryInterface
{

    public function findAll(array $params)
    {
        $query = Employee::query();
        $query->with('company:id,name');
        $results = $query->get();
        return [
            'rows' => $results->toArray(),
            'count' => $results->count(),
        ];
        return $results;
    }


    public function create(array $attributes)
    {
        try {
            DB::beginTransaction();
            Employee::create($attributes);
            DB::commit();
            return [];
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function update($id, array $attributes)
    {
        try {
            DB::beginTransaction();
            Employee::where('id', $id)->update($attributes);
            DB::commit();
            return [];
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function destroy($id)
    {
        return Employee::destroy($id);
    }

    public function findById($id)
    {
        $query = Employee::query();
        $query->where('id', $id);
        $results = $query->get()[0] ?? null;
        return $results;
    }

}
