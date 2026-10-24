<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblUsuarios extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $primaryKey = 'id_users';
    public $table      =  'tbl_users';
    
    protected $fillable = [
        'id_users',
        'id_rol_users',
        'id_employee',
        'busines_mail',
        'password',
        'active'
    ];
}
