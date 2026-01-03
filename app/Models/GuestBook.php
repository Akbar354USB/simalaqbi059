<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestBook extends Model
{
    protected $fillable = ['guest_name', 'number_phone',  'agency_id', 'objective', 'arrival_time'];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_guest_books');
    }

    // buku tamu milik satu agency
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
