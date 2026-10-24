<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblEmpresas extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $primaryKey = 'id_users';
    public $table      = 'tbl_companies';

    protected $fillable = [
        'id_companies',
        'id_country',
        'Acronym',
        'name',
        'address',
        'phone',
        'slug',
        'state',
        'municipality',
        'tows'
    ];
}
