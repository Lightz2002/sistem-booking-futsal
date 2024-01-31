<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

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
        'user_id',
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

    public function payment(): BelongsTo
    {
        return  $this->belongsTo(Payment::class);
    }

    private $defaultFilters = [
        'search' => '',
        'date_from' => '',
        'date_until' => '',
        'day' => '',
        'field' => '',
        'status' => ''
    ];

    public function scopeFilter($query, $filters = [])
    {
        if (!$filters) {
            $filters = $this->defaultFilters;
        }

        if (in_array($filters['status'], ['upcoming', 'history'])) {
            $filters['status'] = 'confirmed';
        }


        return $query->join('packages as p', 'package_id', '=', 'p.id')
            ->join('fields as f', 'allotments.field_id', '=', 'f.id')
            ->leftJoin('users as u', 'u.id', '=', 'allotments.user_id')
            ->select(DB::raw('allotments.*, u.name user, p.image package_image, f.name field_name, f.image field_image'))
            ->where(function ($query) use ($filters) {
                if ($filters['date_from'] && $filters['date_until']) {
                    $query->whereRaw('DATE_FORMAT(allotments.date, "%Y-%m-%d") >= ? and DATE_FORMAT(allotments.date, "%Y-%m-%d") <= ?', [$filters['date_from'], $filters['date_until']]);
                } else if ($filters['date_from'] && !$filters['date_until']) {
                    $query->whereRaw('DATE_FORMAT(allotments.date, "%Y-%m-%d") >= ?', [$filters['date_from']]);
                } else if ($filters['date_until'] && !$filters['date_from']) {
                    $query->whereRaw('DATE_FORMAT(allotments.date, "%Y-%m-%d") <= ?', [$filters['date_until']]);
                }

                if (isset($filters['day'])) {
                    $query->whereRaw("DAYNAME(allotments.date) = ?", [$filters['day']]);
                }
            })
            ->where('f.name', 'like', '%' . $filters['field'] . '%')
            ->where(function ($query) use ($filters) {
                $query->where('f.name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('allotments.date', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('allotments.start_time', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('allotments.end_time', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('u.name', 'like', '%' . $filters['search'] . '%');
            })
            ->where('allotments.status', 'like', '%' . $filters['status'] . '%');
    }
}
