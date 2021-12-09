<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $keyType = 'string';

    protected $primaryKey = 'code';
    protected $fillable = ['code', 'student_count'];
    protected $casts = ['code' => 'string'];
    protected $with = ['departments', 'periods'];

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class);
    }

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class);
    }
}
