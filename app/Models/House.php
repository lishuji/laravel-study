<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'pro_house';

    protected $casts = [
        'enter_time' => 'datetime:Y-m-d H:i:s',
    ];
}
