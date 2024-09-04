<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function getDepartmentHead()
    {
        return $this->hasMany(TeamDepartmentHead::class, 'team_id', 'id');
    }
    public function teamMember()
    {
        return $this->hasMany(TeamMember::class );
    }

    public function teamDepartment()
    {
        return $this->hasOne(Departments::class, 'id', 'department');
    }
    public function teamOrganization()
    {
        return $this->hasOne(Organization::class, 'id', 'organization');
    }
}
