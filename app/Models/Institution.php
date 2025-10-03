<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'address',
        'city',
        'province',
        'website',
        'industry_for',
        'notes',
        'photo',
    ];

    public function contacts()
    {
        return $this->hasMany(InstitutionContact::class);
    }

    public function quotas()
    {
        return $this->hasMany(InstitutionQuota::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function industryFor()
    {
        return $this->belongsTo(SchoolMajor::class, 'industry_for');
    }
}
