<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    protected static function boot()
    {
        parent::boot();

        // Define a global scope for active employees
        static::addGlobalScope('active', function ($query) {
            $query->where('is_active', 1);
        });
    }
    public function scopeWithInactive($query)
    {
        return $query->withoutGlobalScope('active');
    }

    // Scope to include only inactive employees
    public function scopeOnlyInactive($query)
    {
        return $query->withoutGlobalScope('active')->where('is_active', 0);
    }

    public function empEducation()
    {
        return $this->hasMany(EmployeeEducation::class, 'emp_id', 'emp_id');
    }

    public function empDepartment()
    {
        return $this->hasOne(Departments::class, 'id', 'department');
    }

    public function empDesignation()
    {
        return $this->hasOne(Designations::class, 'id', 'designation');
    }
    public function empOrganization()
    {
        return $this->hasOne(Organization::class, 'id', 'organization');
    }
    public function empDocuments()
    {
        return $this->hasMany(EmployeeDocument::class, 'emp_id', 'emp_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'emp_id', 'emp_id');
    }
    public function role()
    {
        return $this->hasOneThrough(
            Role::class,    // The final model we want to access
            User::class,    // The intermediate model
            'emp_id',       // Foreign key on the User table that refers to Employee
            'id',           // Foreign key on the Role table
            'emp_id',           // Local key on the Employee table
            'role_id'       // Local key on the User table
        );
    }

        public function dutySlot()
    {
        return $this->hasOne(DutySlot::class, 'id', 'duty_slot');
    }
    public function attendanceData($startDate='', $endDate='')
    {
        $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
        $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
        return $this->hasMany(Attendance::class, 'PIN', 'biometric_id')->whereBetween('DateTime', [$startDate, $endDate])->orderBy('DateTime', 'asc')->get();
    }
    public function employeeRequest()
    {
        return $this->hasMany(EmployeeRequest::class, 'emp_id', 'emp_id');
    }
    public function employeeApprovedRequest()
    {
        return $this->hasMany(EmployeeRequest::class, 'emp_id', 'emp_id')->where('approval_status', 2);
    }
    public function allLeave($status=2, $statusBy='id')
    {
        return $this->hasMany(Leave::class, 'emp_id', 'emp_id')
            ->where('approval_status', $status)->get();
    }

    public function leaveDateWise($startDate='', $endDate='')
    {
        return $this->hasOne(Leave::class, 'emp_id', 'emp_id')
            ->where('start_date', '<=', $startDate)
            ->where('end_date', '>=', $endDate)
            ->where('approval_status', 2)
            ->first();
    }

    public function attendanceBetweenDate($startDate='', $endDate='')
    {

    }

    public function attendanceDetails($date='')
    {
        return $this->hasMany(AttendancesDetails::class, 'emp_id', 'emp_id')->where('date', $date)->orderBy('id', 'desc')->get();
    }

    public function empLineManager()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'line_manager');
    }
    public function empDepartmentHead()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'department_head');
    }



    public function team()
    {
        return $this->hasManyThrough(
            Team::class,       // The final model we want to access
            TeamMember::class, // The intermediate model
            'emp_id',          // Foreign key on the TeamMember table referring to the Employee
            'id',              // Foreign key on the Team table
            'emp_id',              // Local key on the Employee table
            'team_id'          // Local key on the TeamMember table referring to the Team
        );
    }
    public function departmentHeads()
    {
        return $this->hasManyThrough(
            TeamDepartmentHead::class, // The intermediate model
            TeamMember::class,       // The final model we want to access
            'emp_id',          // Foreign key on the TeamMember table referring to the Employee
            'team_id',              // Foreign key on the TeamDepartmentHead table
            'emp_id',              // Local key on the Employee table
            'team_id'          // Local key on the TeamMember table referring to the Team
        );
    }

    public function departmentHeadTeam()
    {
        return $this->hasManyThrough(
            TeamDepartmentHead::class, // The intermediate model
            Team::class,       // The final model we want to access
            'emp_id',          // Foreign key on the TeamMember table referring to the Employee
            'team_id',              // Foreign key on the TeamDepartmentHead table
            'emp_id',              // Local key on the Employee table
            'team_id'          // Local key on the TeamMember table referring to the Team
        );
    }
    public function leaveBalance($year = '')
    {
        $leaveCounts = LeaveType::get();
        $applicableLeaveDays = [];

        $year = $year ?: date('Y');  // Use current year if not specified
        $startDate = "$year-01-01";
        $endDate = "$year-12-31";

        $leaveDaysManual = LeaveDaysManual::where('emp_id', $this->emp_id)
            ->where('year', $year)
            ->first();

        foreach ($leaveCounts as $item) {
            $days = $item->days; // Default to the leave type's days

            if ($leaveDaysManual) {
                switch ($item->id) {
                    case 1:
                        $days = $leaveDaysManual->casual_leave;
                        break;
                    case 2:
                        $days = $leaveDaysManual->sick_leave;
                        break;
                    case 3:
                        $days = $leaveDaysManual->annual_leave;
                        break;
                }
            }

            $applicableLeaveDays[] = [
                'id' => $item->id,
                'name' => $item->name,
                'days' => $days,
            ];
        }

        // Get total leave taken for the specified year
        $totalLeave = Leave::where('emp_id', $this->emp_id)
            ->where('approval_status', 2)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->get();

        // Calculate total leave taken for each type
        $leaveBalance = [];
        foreach ($applicableLeaveDays as $item) {
            $usedDays = $totalLeave->where('leave_type', $item['id'])->sum('intended_leave_days');
            $leaveBalance[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'allowance' => $item['days'],
                'taken' => $usedDays,
                'remaining' => $item['days'] - $usedDays,
            ];
        }

        return $leaveBalance;
    }




}
