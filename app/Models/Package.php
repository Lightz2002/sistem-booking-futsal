<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    public function scopeFilter($query, $search = '')
    {
        return $query->where('status', 'active')
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('code', 'like', '%' . $search . '%');
    }

    public function field(): BelongsTo
    {
        return  $this->belongsTo(Field::class);
    }
}
