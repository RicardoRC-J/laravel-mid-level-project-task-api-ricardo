<?php

namespace App\Models;

use App\Traits\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Project extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory, HasUuidTrait, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'description',
        'sattus'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'update_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $auditInclude = [
        'name',
        'description',
        'sattus'
    ];

    protected $auditTimestamps = true;

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    //-- Mis scopes para los filtros --//
    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeFilterByName($query, $name)
    {
        if ($name) {
            return $query->where('name', 'LIKE', "%{$name}%");
        }
        return $query;
    }

    public function scopeFilterByDateRange($query, $from = null, $to = null)
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }
        return $query;
    }
}
