<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $file_name
 * @property string $url
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class UserFiles extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'file_name', 'url'];

    protected $hidden = ['user_id', 'updated_at', 'deleted_at'];

}
