<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designations extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name'];

    protected static function boot()
    {
        parent::boot();

        // Define a global scope for active employees
        static::addGlobalScope('active', function ($query) {
            $query->where('is_active', 1);
        });
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('name', 'asc');
        });
    }

    public function employee($date='')
    {
        return $this->hasMany(Employee::class, 'designation', 'id');
    }
    // public function designation()
    // {
    //     return $this->belongsTo(Designations::class);
    // }
}
