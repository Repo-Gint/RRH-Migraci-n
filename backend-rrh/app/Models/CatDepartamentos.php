<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatDepartamentos extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'id_departaments';
    protected $table      = 'cat_departaments';

    protected $fillable = [
        'id_departaments',
        'departaments_en',
        'departaments_es',
        'acronym',
        'slug',
        'active'
    ];
}
