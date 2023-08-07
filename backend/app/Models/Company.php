<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Company extends Model
{
    use HasFactory;
    use Notifiable;

    protected const FILLABLE_COMPANY_FIELDS = [
        'name',
        'email',
        'logo',
        'website',
    ];

    protected $fillable = self::FILLABLE_COMPANY_FIELDS;

    public function logo()
    {
        return $this->morphMany(Files::class, 'belongsTo')->where('belongsTo_column', '=', 'logo');
    }

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
}
