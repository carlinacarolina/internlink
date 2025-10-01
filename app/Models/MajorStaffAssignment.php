<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MajorStaffAssignment extends Model
{
    use HasFactory;

    protected $table = 'major_staff_assignments';

    protected $fillable = [
        'school_id',
        'supervisor_id',
        'major',
        'major_slug',
    ];

    protected static function booted(): void
    {
        static::creating(function (MajorStaffAssignment $assignment) {
            $assignment->major = trim($assignment->major);
            if (!$assignment->major_slug && $assignment->major !== '') {
                $assignment->major_slug = static::slugFromMajor($assignment->major);
            }
        });

        static::updating(function (MajorStaffAssignment $assignment) {
            $assignment->major = trim($assignment->major);
            if ($assignment->isDirty('major')) {
                $assignment->major_slug = static::slugFromMajor($assignment->major);
            }
        });
    }

    public static function slugFromMajor(string $major): string
    {
        $slug = Str::slug($major, '-');

        return $slug !== '' ? $slug : 'major';
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
}
