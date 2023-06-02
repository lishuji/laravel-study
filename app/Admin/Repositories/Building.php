<?php

namespace App\Admin\Repositories;

use App\Models\Building as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Building extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
