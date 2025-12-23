<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestBook extends Model
{
    protected $fillable = ['guest_name', 'number_phone', 'agency', 'objective', 'arrival_time'];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_guest_books');
    }
}
