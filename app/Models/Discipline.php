<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discipline extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'course',
        'year',
        'semester',
        'abbreviation',
        'name',
        'name_pt',
        'ECTS',
        'hours',
        'optional',
    ];

    protected function casts(): array
    {
        return [
            'optional' => 'boolean',
        ];
    }

    public function courseModel(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course', 'abbreviation');
    }
}
