<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $company_id
 * @property string $name_cn
 * @property string $name_en
 * @property string $game_code
 * @property int $status
 * @property string $updated_at
 * @property string $created_at
 */
class Game extends BaseModel
{
    protected $table = 'game';

    protected $fillable = [
        'company_id',
        'name_cn',
        'name_en',
        'game_code',
        'status',
    ];

    public function getNameAttribute()
    {
        return $this->name_en;
    }
}