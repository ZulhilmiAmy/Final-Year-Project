<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['patient_name', 'date'];
    public $timestamps = false; // kalau table tiada created_at, updated_at
}
