<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'school_id',
        'year',
        'term',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
