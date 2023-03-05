<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'users';

//    protected $primaryKey = 'id';

//    public $incrementing = false;

//    protected $keyType = 'string';

//    public $timestamps = false;

//    protected $dateFormat = 'U';

//    const CREATED_AT = 'creation_date';

//    const UPDATED_AT = 'last_update';

    protected $connection = 'mysql';

    protected $attributes = [
        'password' => '1234567'
    ];

    /**
     * 全局作用域
     */
    protected static function booted()
    {
        static::addGlobalScope('age', function (Builder $builder) {
            $builder->where('age', '>', 200);
        });
    }

    /**
     * 局部作用域
     * @param $query
     * @return mixed
     */
    public function scopePopular($query)
    {
        return $query->where('votes', '>', 100);
    }

}
