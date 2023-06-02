<?php

namespace App\Models;

use DateTimeInterface;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
