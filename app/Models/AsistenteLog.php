<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsistenteLog extends Model
{
    use HasFactory;

    protected $table = 'asistente_log';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
