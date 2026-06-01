<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'abbreviation';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'abbreviation',
        'name',
        'name_pt',
        'type',
        'semesters',
        'ECTS',
        'places',
        'contact',
        'objectives',
        'objectives_pt',
    ];

    public function disciplines(): HasMany
    {
        return $this->hasMany(Discipline::class, 'course', 'abbreviation');
    }
}
