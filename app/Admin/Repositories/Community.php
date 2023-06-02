<?php

namespace App\Admin\Repositories;

use App\Models\Community as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Community extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
