<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'department',
        'office',
        'extension',
        'locker',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function departmentModel(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department', 'abbreviation');
    }
}
