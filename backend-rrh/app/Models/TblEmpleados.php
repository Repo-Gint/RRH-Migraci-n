<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblEmpleados extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $primaryKey = 'id_employee';
    public $table      = 'tbl_employee';

    protected $fillable = [
        'id_employee',
        'id_position',
        'id_type_employee',
        'id_company',
        'id_parent',
        'id_output',
        'slug',
        'name',
        'paternal',
        'maternal',
        'photo',
        'busines_mail',
        'active'  
    ];

}
