<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatUsoCfdi extends Model
{
    use HasFactory;

    protected $table = 'sat_usos_cfdi';

    protected $primaryKey = 'clave';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];
}
