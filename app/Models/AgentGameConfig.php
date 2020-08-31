<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $agent_id
 * @property string $game_id
 * @property int $status
 * @property string $updated_at
 * @property string $created_id
 */
class AgentGameConfig extends BaseModel
{
    protected $table = 'agent_game_config';

    protected $casts = [
        'id' => 'integer',
        'agent_id' => 'integer',
        'game_id' => 'integer',
        'status' => 'integer',
        'updated_at' => 'string',
        'created_at' => 'string',
    ];

    public function game()
    {
        return $this->hasOne(Game::class, 'id', 'game_id');
    }
}