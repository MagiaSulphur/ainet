<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'number',
        'course',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courseModel(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course', 'abbreviation');
    }
}
