<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'pro_building';

    protected $fillable = [
        'community_code',
        'code',
        'name',
        'house',
        'lift',
        'desc',
    ];

}
