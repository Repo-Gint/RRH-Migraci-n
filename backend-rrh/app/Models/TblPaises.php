<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblPaises extends Model
{
    use HasFactory;

    public    $timestamps = false;
    public    $primaryKey = 'id_countries';
    protected $table      = 'tbl_countries';

    protected $fillable = [
        'id_countries',
        'country',
        'flag',
        'slug'
    ];
}
