<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $updated_at
 * @property string $created_at
 */
class GameCompany extends BaseModel
{
    protected $table = 'game_company';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'name',
        'status',
    ];

    public function game()
    {
        return $this->hasMany(Game::class, 'company_id');
    }

    public function GameCompanyMaintenance()
    {
        return $this->morphMany(GameCompanyMaintenance::class, 'maintainable');
    }

    public function GameCompanyCommission()
    {
        return $this->hasOne(GameCompanyCommission::class, 'company_id');
    }
}
