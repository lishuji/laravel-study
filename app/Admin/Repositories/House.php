<?php

namespace App\Admin\Repositories;

use App\Models\House as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class House extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
