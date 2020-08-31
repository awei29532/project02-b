<?php

namespace App\Models;

use App\Models\BaseModel;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property int $site_id
 * @property int $id
 * @property int $user_id
 * @property int $parent_id
 * @property int $level
 * @property string $cell_phone
 * @property string $remark
 * @property string $invitation_code
 * @property string  $updated_at
 * @property string $created_at
 */
class Agent extends BaseModel
{
    use NodeTrait;

    protected $table = 'agent';

    protected $fillable = [
        'id',
        'user_id',
        'parent_id',
        'cell_phone',
        'remark',
    ];

    protected $hidden = [
        '_lft',
        '_rgt',
    ];

    public function wallet()
    {
        return $this->hasOne(AgentWallet::class, 'agent_id');
    }

    public function member()
    {
        return $this->hasMany(Member::class, 'agent_id');
    }

    public function owner()
    {
        return $this->ancestors[0] ?? null;
    }

    public function isOwner()
    {
        return $this->parent_id == null;
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function allowAgentId(int $id)
    {
        $child = Agent::find($id);
        return $child ? $child->isDescendantOf($this) : false;
    }

    public function children()
    {
        return $this->descendants;
    }

    public function gameCompanySetting()
    {
        return $this->hasMany(AgentGameCompanyConfig::class, 'agent_id');
    }

    public function gameSetting()
    {
        return $this->hasMany(AgentGameConfig::class, 'agent_id');
    }

    public function generatorInvitationCode($username)
    {
        $this->invitation_code = 'A' . mb_substr(sha1($username), 0, 5);
    }
}
