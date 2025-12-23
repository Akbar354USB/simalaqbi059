<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategories extends Model
{
    protected $fillable = ['categories_id', 'name'];

    // Relasi: Subkategori milik satu kategori
    public function categories()
    {
        return $this->belongsTo(Categories::class);
    }

    // Relasi: Satu sub-kategroi memiliki banyak item
    public function items()
    {
        return $this->hasMany(Items::class);
    }
}
