<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'field_id',
        'valid_end',
        'image',
        'status'
    ];

    public function scopeFilter($query, $search = '', $status = '')
    {
        return $query->where('status', $status)
            ->where(function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
    }

    public function field(): BelongsTo
    {
        return  $this->belongsTo(Field::class);
    }

    public function package_details(): HasMany
    {
        return  $this->hasMany(PackageDetail::class);
    }
}
