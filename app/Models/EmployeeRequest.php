<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeRequest extends Model
{
    use SoftDeletes;
    use HasFactory;

    //protected $table='all_requests';
    public function scopeOnlyLeaveRequest($query)
    {
        return $query->where('request_type', 'leave-request');
    }
    public function scopeOnlyLateArrivalRequest($query)
    {
        return $query->where('request_type', 'late-arrival');
    }
    public function scopeOnlyEarlyExitRequest($query)
    {
        return $query->where('request_type', 'early-exit');
    }
    public function scopeOnlyHomeOfficeRequest($query)
    {
        return $query->where('request_type', 'home-office');
    }
    public function scopeOnlyPending($query)
    {
        return $query->where('approval_status', 1);
    }
    public function scopeOnlyApproved($query)
    {
        return $query->where('approval_status', 2);
    }
    public function scopeOnlyLineManagerApproved($query)
    {
        return $query->whereNotNull('line_manager_approved_by');
    }
    public function employee()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'emp_id');
    }
    public function approvalStatus()
    {
        return $this->belongsTo(ApprovalStatus::class, 'approval_status');
    }
    public function departmentHeadApproved()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'department_head_approved_by');
    }

    public function lineManagerApproved()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'line_manager_approved_by');
    }

    public function rejectedBy()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'rejected_by');
    }

}
