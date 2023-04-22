<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/*
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property int $level
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */

class Category extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'parent_id',
        'level',
        'description',
    ];

    protected $attributes = [
        'parent_id' => 0,
        'level' => 0,
    ];
}
