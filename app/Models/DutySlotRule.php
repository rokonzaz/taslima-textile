<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DutySlotRule extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function dutySlot(){
        return $this->hasOne(DutySlot::class, 'id', 'duty_slot_id');
    }
}
