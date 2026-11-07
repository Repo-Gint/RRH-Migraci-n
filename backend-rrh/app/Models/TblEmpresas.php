<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblEmpresas extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id_companies';
    protected $table      = 'tbl_companies';

    protected $fillable = [
        'id_companies',
        'id_country',
        'acronym',
        'name',
        'address',
        'phone',
        'state',
        'municipality',
        'towns'
    ];
}
