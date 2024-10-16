<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointments';

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_date',
        'status'
    ];

    public function patient() {
        return $this->belongsTo(User::class, 'patient_id');
    }
    
    public function doctor() {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    
    
}
