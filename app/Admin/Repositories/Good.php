<?php

namespace App\Admin\Repositories;

use App\Models\Good as GoodModel;
use Dcat\Admin\Repositories\EloquentRepository;

class Good extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = GoodModel::class;

    public function getGridColumns()
    {
        return ['id', 'category_id', 'code', 'name', 'description', 'image', 'price', 'stock', 'created_at', 'updated_at'];
    }
}
