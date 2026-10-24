<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblSessions extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $primaryKey = 'id_sessions';
    public $table      = 'tbl_session';

    protected $fillable = [
        'id_session',
        'id_users',
        'token'
    ];
}
