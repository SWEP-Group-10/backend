<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    use HasFactory;

    public $keyType = 'string';

    protected $primaryKey = 'code';
    protected $fillable = ['code', 'name', 'size', 'availability'];
    protected $casts = [
        'code' => 'string',
        'availability' => 'boolean',
    ];

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class);
    }
}
