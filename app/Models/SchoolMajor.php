<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SchoolMajor extends Model
{
    use HasFactory;

    protected $table = 'school_majors';

    protected $fillable = [
        'school_id',
        'name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (SchoolMajor $major) {
            $major->name = trim($major->name);
            if (!$major->slug && $major->name !== '') {
                $major->slug = static::slugFromName($major->name);
            }
        });

        static::updating(function (SchoolMajor $major) {
            $major->name = trim($major->name);
            if ($major->isDirty('name')) {
                $major->slug = static::slugFromName($major->name);
            }
        });
    }

    public static function slugFromName(string $name): string
    {
        $slug = Str::slug($name, '-');
        return $slug !== '' ? $slug : 'major';
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'major_id');
    }

    public function supervisors()
    {
        return $this->hasMany(Supervisor::class, 'department_id');
    }

    public function majorStaffAssignments()
    {
        return $this->hasMany(MajorStaffAssignment::class, 'major_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }
}
