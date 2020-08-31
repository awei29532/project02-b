<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public $timestamps  = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public static function boot()
    {
        parent::boot();
    }
}
