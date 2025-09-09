<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestingData extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'status',
        'priority',
        'created_by',
        'page_type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePage1($query)
    {
        return $query->where('page_type', 'page1');
    }

    public function scopePage2($query)
    {
        return $query->where('page_type', 'page2');
    }

    public function scopePage3($query)
    {
        return $query->where('page_type', 'page3');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }
}
