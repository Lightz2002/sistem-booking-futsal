<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Field extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'status',
    ];

    private $defaultFilters = [
        'search' => '',
        'date_from' => '',
        'date_until' => '',
        'day' => '',
        'field' => ''
    ];

    public function scopeFilter($query, $search = '')
    {
        return $query->where('status', 'active')
            ->where('name', 'like', '%' . $search . '%');
    }

    public function scopeFilterBookingField($query, $filters = [])
    {
        if (!$filters) {
            $filters = $this->defaultFilters;
        }

        return $query->join('allotments as a', 'a.field_id', '=', 'fields.id')
            ->join('packages as p', 'a.package_id', '=', 'p.id')
            ->select(DB::raw('fields.id, fields.name, fields.image, MIN(a.start_time) start_time, MAX(a.end_time) end_time'))
            ->where(function ($query) use ($filters) {
                if ($filters['date_from'] && $filters['date_until']) {
                    $query->whereRaw('DATE_FORMAT(a.date, "%Y-%m-%d") >= ? and DATE_FORMAT(a.date, "%Y-%m-%d") <= ?', [$filters['date_from'], $filters['date_until']]);
                } else if ($filters['date_from'] && !$filters['date_until']) {
                    $query->whereRaw('DATE_FORMAT(a.date, "%Y-%m-%d") >= ?', [$filters['date_from']]);
                } else if ($filters['date_until'] && !$filters['date_from']) {
                    $query->whereRaw('DATE_FORMAT(a.date, "%Y-%m-%d") <= ?', [$filters['date_until']]);
                }

                if (isset($filters['day'])) {
                    $query->whereRaw("DAYNAME(a.date) = ?", [$filters['day']]);
                }
            })
            ->where('fields.name', 'like', '%' . $filters['field'] . '%')
            ->where('fields.name', 'like', '%' . $filters['search'] . '%')
            ->groupBy('fields.id')
            ->orderBy('fields.name');
    }
}
