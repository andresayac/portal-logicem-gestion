<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentsLog extends Model
{
    use HasFactory;

    // set table name
    protected $table = 'documents_log';

    protected $fillable = [
        'user_id',
        'document_type',
        'request_body',
        'response_body',
        'response_code'

    ];

    // belogsTo user relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
