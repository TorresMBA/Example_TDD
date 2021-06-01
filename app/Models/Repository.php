<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'description'];

    public function user()
    {
        //un repository registro pertenece a un usuario
        return $this->belongsTo(User::class);
    }
}
