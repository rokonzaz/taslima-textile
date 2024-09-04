<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DutySlot extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function dutySlotRule($date='')
    {
        return $this->hasOne(DutySlotRule::class, 'duty_slot_id')
            ->where('rule_for', 'duty_slot')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->orderBy('start_date', 'desc')
            ->first();
    }
    public function employee($date='')
    {
        return $this->hasMany(Employee::class, 'duty_slot', 'id');
    }
}
