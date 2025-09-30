<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'email',
        'website',
    ];

    protected static function booted(): void
    {
        static::creating(function (School $school) {
            if (!empty($school->code)) {
                return;
            }

            $base = Str::upper(Str::slug($school->name ?? 'school', ''));
            if ($base === '') {
                $base = 'SCH';
            }
            $base = substr($base, 0, 8);
            $candidate = $base;
            $suffix = 1;

            while (static::where('code', $candidate)->exists()) {
                $candidate = substr($base, 0, 6) . str_pad((string) $suffix, 2, '0', STR_PAD_LEFT);
                $suffix++;
            }

            $school->code = $candidate;
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function supervisors()
    {
        return $this->hasMany(Supervisor::class);
    }

    public function institutions()
    {
        return $this->hasMany(Institution::class);
    }

    public function periods()
    {
        return $this->hasMany(Period::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function internships()
    {
        return $this->hasMany(Internship::class);
    }

    public function monitoringLogs()
    {
        return $this->hasMany(MonitoringLog::class);
    }
}
