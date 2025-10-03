<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MajorStaffAssignment extends Model
{
    use HasFactory;

    protected $table = 'major_staff_assignments';

    protected $fillable = [
        'school_id',
        'supervisor_id',
        'major',
        'major_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (MajorStaffAssignment $assignment) {
            $assignment->major = trim($assignment->major);
        });

        static::updating(function (MajorStaffAssignment $assignment) {
            $assignment->major = trim($assignment->major);
        });
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function major()
    {
        return $this->belongsTo(SchoolMajor::class, 'major_id');
    }
}
