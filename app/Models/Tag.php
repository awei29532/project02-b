<?php

namespace App;

use App\Models\BaseModel;

/**
 * @property int $id
 * @property string $name
 * @property string $color
 * @property string $remark
 */
class Tag extends BaseModel
{
    protected $table = 'tag';

    public $timestamps = false;
}
