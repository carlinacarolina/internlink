<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'internship_id',
        'supervisor_id',
        'log_date',
        'title',
        'content',
        'type',
    ];

    protected $casts = [
        'log_date' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
