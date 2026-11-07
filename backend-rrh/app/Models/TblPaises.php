<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblPaises extends Model
{
    use HasFactory;

    public    $timestamps = false;
    protected $primaryKey = 'id_country';
    protected $table      = 'tbl_countries';

    protected $fillable = [
        'id_country',
        'country',
        'flag',
        'slug'
    ];
}
