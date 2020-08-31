<?php

namespace App\Models;

/**
 * @property int $company_id
 * @property string $ratio_win
 * @property string $ratio_lose
 * @property string $status
 * @property string $updated_at
 * @property string $created_at
 */
class GameCompanyCommission extends BaseModel
{
    protected $table = 'game_company_commission';

    protected $primaryKey = 'company_id';

    protected $fillable = [
        'company_id',
        'ratio_win',
        'ratio_lose',
        'status'
    ];

    protected $hidden = [
        'company_id',
        'created_at',
        'updated_at'
    ];

    public function GameCompany()
    {
        return $this->belongsTo(GameCompany::class);
    }
}
