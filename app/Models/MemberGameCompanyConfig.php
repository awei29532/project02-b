<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $member_id
 * @property int $company_id
 * @property int $status
 * @property string $updated_at
 * @property string $created_at
 */
class MemberGameCompanyConfig extends BaseModel
{
    protected $table = 'member_game_company_config';

    protected $fillable = [
        'member_id',
        'company_id',
        'status',
    ];

    public function company()
    {
        return $this->hasOne(GameCompany::class, 'id', 'company_id');
    }
}
