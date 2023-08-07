<?php

namespace App\Repositories\Eloquent;

use App\Models\Company;
use App\Notifications\CompanyNotification;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Support\Facades\DB;





class CompaniesRepository implements EloquentRepositoryInterface
{
    public function findAll(array $params)
    {
        $query = Company::query();
        $results = $query->get();
        return [
            'rows' => $results->toArray(),
            'count' => $results->count(),
        ];
        return $results;
    }

    public function create(array $attributes)
    {
        $company = Company::create([
                'name' => $attributes['name'] ?? null,
                'email' => $attributes['email'] ?? null,
                'website' => $attributes['website'] ?? null,
                'logo' => $attributes['logo']['publicUrl'] ?? null
                ]);

        $company->notify((new CompanyNotification($company->name, $company->id)));

        return $company;

    }

    public function update($id, array $attributes)
    {
        try {
            DB::beginTransaction();
            Company::where('id', $id)->update([
                'name' => $attributes['name'] ?? null,
                'email' => $attributes['email'] ?? null,
                'website' => $attributes['website'] ?? null,
                'logo' => $attributes['logo']['publicUrl'] ?? null
            ]);
            DB::commit();
            return [];
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public function destroy($id)
    {
        return Company::destroy($id);
    }

    public function findById($id)
    {
        $query = Company::query();
        $query->where('id', $id);
        $results = $query->get()[0] ?? null;
        return $results;
    }

    public function findAllAutocomplete(array $params)
    {
        $query = Company::query();

        $query->select('*', 'name as label');

        if (isset($params['query'])) {
            $query->where('name', 'like', '%'.$params['query'].'%');
        }

        if (isset($params['limit'])) {
            $query->limit($params['limit']);
        }

        $query->orderBy('name', 'ASC');

        return $query->get()
            ->map(fn($item) => ['id' => $item->id, 'label' => $item->label]);
    }
}
