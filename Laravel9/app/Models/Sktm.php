<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sktm extends Model
{
    use HasFactory;
    protected $table = 'sktm';

    protected $primaryKey = 'id_sktm';
}
