<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatPosiciones extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'id_positions';
    protected $table      = 'cat_positions';

    protected $fillable = [
        'id_positions',
        'id_parent',
        'id_hierarchy',
        'id_departament',
        'code',
        'position_es',
        'position_en',
        'description',
        'responsability',
        'vacancies',
        'slug',
        'active',
    ];
}
