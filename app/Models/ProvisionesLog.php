<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvisionesLog extends Model
{
    use HasFactory;

    // set table name
    protected $table = 'provisiones_log';

    // belogsTo user relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRemesasAttribute(): array
    {
        $remesas = [];

        if (empty($this->request_body)) return $remesas;

        $request_body = json_decode($this->request_body);

        foreach ($request_body->JournalEntryLines as $JournalEntryLine) {
            $remesas[$JournalEntryLine->Reference1] = $JournalEntryLine->Reference1;
        }

        return $remesas;
    }

    public function getJdtNumAttribute()
    {
        if ($this->response_code != 201)
            return null;

        $response_body = json_decode($this->response_body);
        return $response_body->JdtNum;
    }
}
