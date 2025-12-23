<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uploads extends Model
{
    protected $fillable = ['item_documents_id', 'file_path'];

    // Upload milik satu item_document
    public function item_document()
    {
        return $this->belongsTo(ItemDocuments::class, 'item_documents_id');
    }
}
