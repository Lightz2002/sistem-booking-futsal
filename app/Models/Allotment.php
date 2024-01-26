<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Allotment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'package_id',
        'date',
        'field_id',
        'start_time',
        'end_time',
        'price',
        'status'
    ];

    public function package(): BelongsTo
    {
        return  $this->belongsTo(Package::class);
    }

    public function field(): BelongsTo
    {
        return  $this->belongsTo(Field::class);
    }
}
