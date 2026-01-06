<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Kategori;

class MasterItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'kode',
        'nama',
        'harga_beli',
        'laba',
        'supplier',
        'jenis',
        'foto',
    ];

    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'kategori_master_item', 'master_item_id', 'kategori_id');
    }
}
