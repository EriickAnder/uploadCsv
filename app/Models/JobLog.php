<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobLog extends Model
{
    protected $fillable = [
        'uuid',
        'status',
        'payload',
        'error',
        'started_at',
        'finished_at',
    ];
}
