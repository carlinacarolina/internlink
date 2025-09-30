<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'institution_id',
        'period_id',
        'status',
        'student_access',
        'submitted_at',
        'notes',
    ];

    protected $casts = [
        'student_access' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
