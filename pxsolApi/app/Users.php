<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Users extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'last_name', 'created_at', 'updated_at', 'deleted_at'];

}
