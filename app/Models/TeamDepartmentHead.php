<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamDepartmentHead extends Model
{
    use HasFactory;

    public function employee()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'department_head_id');
    }
}
