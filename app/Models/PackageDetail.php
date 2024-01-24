<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'package_id ',
        'start_time',
        'end_time',
        'price',
    ];

    public function scopeFilter($query, $search = '')
    {
        return $query->where('start_time', 'like', '%' . $search . '%')
            ->orWhere('end_time', 'like', '%' . $search . '%')
            ->orWhere('price', 'like', '%' . $search . '%');
    }

    public function package(): BelongsTo
    {
        return  $this->belongsTo(Package::class);
    }
}
