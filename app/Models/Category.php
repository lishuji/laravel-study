<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Dcat\Admin\Traits\ModelTree;
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
    use ModelTree;

    protected $fillable = [
        'name',
        'parent_id',
        'level',
        'description',
        'status'
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'level' => 'integer',
        'status' => 'boolean'
    ];

    protected $attributes = [
        'parent_id' => 1,
        'level' => 0,
        'status' => 0
    ];

    protected $titleColumn = 'name';

    protected $orderColumn = 'created_at';

    protected $parentColumn = 'parent_id';
}
