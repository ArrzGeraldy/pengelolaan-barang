<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    protected $guarded = ['id'];
    
    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class, 'jurusan_id');
    }
}
