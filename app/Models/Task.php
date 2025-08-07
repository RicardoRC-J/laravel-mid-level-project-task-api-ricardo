<?php

namespace App\Models;

use App\Traits\HasUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Task extends Model implements Auditable
{
    use HasFactory, HasUuidTrait, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date'
    ];

    protected $casts = [
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Auditing configuration
    protected $auditInclude = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date'
    ];

    protected $auditTimestamps = true;

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Scopes para filtros dinámicos
    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeFilterByPriority($query, $priority)
    {
        if ($priority) {
            return $query->where('priority', $priority);
        }
        return $query;
    }

    public function scopeFilterByProject($query, $projectId)
    {
        if ($projectId) {
            return $query->where('project_id', $projectId);
        }
        return $query;
    }

    public function scopeFilterByDueDateRange($query, $from = null, $to = null)
    {
        if ($from) {
            $query->whereDate('due_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('due_date', '<=', $to);
        }
        return $query;
    }

    public function scopeFilterByTitle($query, $title)
    {
        if ($title) {
            return $query->where('title', 'LIKE', "%{$title}%");
        }
        return $query;
    }
}
