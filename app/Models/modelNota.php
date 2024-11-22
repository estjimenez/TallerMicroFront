<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class modelNota extends Model
{
    use HasFactory;

    protected $table = 'notas';

    public $timestamps = false;

    protected $fillable = [
        'actividad',
        'nota',
        'codEstudiante'
    ];
}
