<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected const FILLABLE_EMPLOYEE_FIELDS = [
        'first_name',
        'last_name',
        'company_id',
        'email',
        'phone',
    ];

    protected $fillable = self::FILLABLE_EMPLOYEE_FIELDS;

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
