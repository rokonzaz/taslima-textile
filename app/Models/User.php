<?php

namespace App\Models;


use App\Traits\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasPermissionsTrait;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'emp_id');
    }
    public function departmentHeadEmployees()
    {
        return $this->hasMany(Employee::class, 'department_head', 'emp_id');
    }
    public function lineManagerEmployees()
    {
        return $this->hasMany(Employee::class, 'line_manager', 'emp_id');
    }
    public function manageableEmployees()
    {
        return $this->hasMany(Employee::class, 'line_manager', 'emp_id')
            ->orWhere('department_head', $this->emp_id);
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function permissions()
    {
        return $this->hasManyThrough(
            Permission::class,
            RolePermission::class,
            'role_id', // Foreign key on role_permissions table
            'id', // Foreign key on permissions table
            'role_id', // Local key on users table
            'permission_id' // Local key on role_permissions table
        );
    }
    public function organization()
    {
        return $this->hasOne(Organization::class, 'id', 'organization_id');
    }
    public function supervisedTeam()
    {
        return $this->hasManyThrough(
            Team::class,
            TeamDepartmentHead::class,
            'department_head_id',
            'id',
            'emp_id',
            'team_id'
        );
    }

    public function supervisedTeamMembers()
    {

    }


}
