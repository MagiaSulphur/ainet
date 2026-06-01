<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'abbreviation';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'abbreviation',
        'name',
        'name_pt',
    ];

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class, 'department', 'abbreviation');
    }
}
