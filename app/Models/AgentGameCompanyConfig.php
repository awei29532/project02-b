<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $agent_id
 * @property int $company_id
 * @property int $status
 * @property string $updated_at
 * @property string $created_id
 */
class AgentGameCompanyConfig extends BaseModel
{
    protected $table = 'agent_game_company_config';

    protected $fillable = [
        'agent_id',
        'company_id',
        'status',
    ];

    public function company()
    {
        return $this->hasOne(GameCompany::class, 'id', 'company_id');
    }
}