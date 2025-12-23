<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $fillable = ['sub_categories_id', 'name', 'required_document'];

    // Relasi: item milik satu sub_category
    public function sub_categories()
    {
        return $this->belongsTo(SubCategories::class);
    }

    // Relasi: Satu item memiliki banyak item-dokumen
    public function item_documents()
    {
        return $this->hasMany(ItemDocuments::class, 'item_id');
    }
}
