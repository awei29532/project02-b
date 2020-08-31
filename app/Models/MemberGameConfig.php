<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $member_id
 * @property int $game_id
 * @property int $status
 * @property string $updated_at
 * @property string $created_at
 */
class MemberGameConfig extends BaseModel
{
    protected $table = 'member_game_config';

    protected $fillable = [
        'member_id',
        'game_id',
        'status',
    ];

    public function game()
    {
        return $this->hasOne(Game::class, 'id', 'game_id');
    }
}
