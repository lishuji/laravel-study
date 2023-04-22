<?php

namespace App\Admin\Repositories;

use App\Models\Category as CategoryModel;
use Dcat\Admin\Repositories\EloquentRepository;

class Category extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = CategoryModel::class;

    public function getGridColumns()
    {
        return ['id', 'parent_id', 'name', 'created_at', 'updated_at'];
    }
}
