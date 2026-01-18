<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_name'
    ];

    // 1 agency punya banyak buku tamu
    public function guestBooks()
    {
        return $this->hasMany(GuestBook::class);
    }
}
