<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property int $agent_id
 * @property double $amount
 * @property string $udpated_at
 * @property string $created_at
 */
class AgentWallet extends BaseModel
{
    protected $table = 'agent_wallet';

    protected $primaryKey = 'agent_id';

    protected $casts = [
        'agent_id' => 'integer',
        'amount' => 'double',
    ];
}