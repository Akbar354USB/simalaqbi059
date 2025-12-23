<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDocuments extends Model
{
    protected $fillable = ['item_id', 'document_name'];

    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }

    // satu dokumen → satu upload
    public function upload()
    {
        return $this->hasOne(Uploads::class, 'item_documents_id');
    }
}
