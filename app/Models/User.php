<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int $id
 * @property int $identity
 * @property string $username
 * @property string $password
 * @property string $nickname
 * @property int $status
 * @property string $image
 * @property string  $updated_at
 * @property string $created_at
 */
class User extends Auth implements JWTSubject
{
    use HasRoles;
    use HasPermissions;

    protected $table = 'user';

    public $timestamps  = false;

    protected $fillable = [
        'id',
        'identity',
        'username',
        'password',
        'nickname',
        'status',
        'image',
        'updated_at',
        'created_at',
    ];

    protected $hidden = [
        'password',
        'agent',
        'customerService',
        'mainAccount',
    ];

    protected $appends = [
        'detail',
    ];

    public function agent()
    {
        return $this->hasOne(Agent::class, 'user_id', 'id');
    }

    public function customerService()
    {
        return $this->hasOne(CustomerService::class, 'user_id', 'id');
    }

    public function getDetailAttribute()
    {
        switch ($this->identity) {
            case 2:
                return $this->customerService;
            case 3:
                return $this->isSubAccount() ? $this->mainAccount->mainAccount->agent : $this->agent;
        }
    }

    public function isAdmin()
    {
        return $this->identity == 1;
    }

    public function isCustomerService()
    {
        return $this->identity == 2;
    }

    public function isAgent()
    {
        return $this->identity == 3;
    }

    public function isSubAccount()
    {
        return $this->mainAccount ? 1 : 0;
    }

    public function subAccount()
    {
        return $this->hasMany(SubAccount::class, 'extend_id');
    }

    public function mainAccount()
    {
        return $this->hasOne(SubAccount::class);
    }

    public function allowAgentId(int $id)
    {
        if ($this->isAgent()) {
            return $this->isSubAccount() ? $this->mainAccount->mainAccount->agent->allowAgentId($id) : $this->detail->allowAgentId($id);
        } else {
            return true;
        }
    }

    public function distinctLoginLog()
    {
        return $this->hasMany(UserLoginLog::class, 'user_id')->selectRaw('distinct(`ip`)');
    }

    public function latestLoginLog()
    {
        return $this->hasOne(UserLoginLog::class, 'user_id')->latest();
    }

    public function ipExists()
    {
        $ip_arr = $this->distinctLoginLog->map(function ($row) {
            return $row->ip;
        })->toArray();

        /** @var User */
        $user = auth()->user();
        $limit_agent_id_arr = $user->isAgent() ? $user->agent->children()->map(function ($row) {
            return $row->id;
        })->toArray() : [];
        $limit_user_id_arr = $user->isAgent() ? $user->agent->children()->map(function ($row) {
            return $row->user_id;
        })->toArray() : [];

        $id = $this->id;
        $ip_str = '"' . implode('", "', $ip_arr) . '"';
        $limit_user_id_str = '"' . implode('", "', $limit_user_id_arr) . '"';
        $limit_agent_ids_str = '"' . implode('", "', $limit_agent_id_arr) . '"';
        $user_login_ip_exists = "SELECT * FROM `user_login_log` WHERE `user_id` != $id AND `ip` IN ($ip_str) " . ($limit_user_id_str ? "AND `user_id` IN ($limit_user_id_str) " : '') . "LIMIT 1";
        $member_login_ip_exists = "SELECT * FROM `member_login_log` WHERE `ip` IN ($ip_str) " . ($limit_agent_ids_str ? "AND `member_id` IN (SELECT `id` FROM `member` WHERE `agent_id` IN ($limit_agent_ids_str)) " : '') . "LIMIT 1";
        $member_register_ip_exists = "SELECT * FROM `member` WHERE `register_ip` IN ($ip_str) " . ($limit_agent_ids_str ? "AND `agent_id` IN ($limit_agent_ids_str) " : '') . "LIMIT 1";
        $sql = "SELECT IF (EXISTS($user_login_ip_exists) OR EXISTS($member_login_ip_exists) OR EXISTS($member_register_ip_exists), 1, 0) AS `ip_exists`";

        return DB::select($sql)[0]->ip_exists;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
