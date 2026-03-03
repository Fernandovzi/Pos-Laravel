<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatRegimenFiscal extends Model
{
    use HasFactory;

    protected $table = 'sat_regimenes_fiscales';

    protected $primaryKey = 'clave';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];
}
