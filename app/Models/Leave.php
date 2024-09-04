<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function scopeOnlyPending($query)
    {
        return $query->where('approval_status', 1);
    }
    public function scopeOnlyApproved($query)
    {
        return $query->where('approval_status', 2);
    }
    public function employee()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'emp_id');
    }
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type');
    }
    public function requisitionType()
    {
        return $this->belongsTo(RequisitionType::class, 'requisition_type');
    }
    public function approvalStatus()
    {
        return $this->belongsTo(ApprovalStatus::class, 'approval_status');
    }
    public function leaveDocument()
    {
        return $this->hasMany(LeaveDocument::class, 'leave_id', 'id');
    }
    public function updatedBy()
    {
        return $this->hasOne(User::class, 'id', 'updated_by')->withTrashed();
    }


    public function leaveReliever()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'reliever_emp_id');
    }


    public function approvedBy()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'approved_by');
    }
    public function rejectedBy()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'rejected_by');
    }
    public function lineManagerApproved()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'line_manager_approved_by');
    }

    public function departmentHeadApproved()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'department_head_approved_by');
    }



}
