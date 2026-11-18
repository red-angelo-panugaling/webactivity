<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'position',
        'office',
        'age',
        'start_date',
        'salary',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'start_date' => 'date',
            'salary' => 'decimal:2',
        ];
    }
}
