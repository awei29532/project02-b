<?php

namespace App\Models;

use App\MemberTagMapping;
use Hash;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int $site_id
 * @property int $agent_id
 * @property string $username
 * @property string $password
 * @property string $nickname
 * @property int $status
 * @property int $level_id
 * @property int $rebate_id
 * @property string $cell_phone
 * @property string $id_number
 * @property string $country
 * @property string $email
 * @property string $address
 * @property string $line
 * @property string $remark
 * @property string $register_ip
 * @property string $birthday
 * @property string $invitation_code
 * @property string $bind_code
 * @property string $updated_at
 * @property string $created_at
 */
class Member extends BaseModel
{
    use SoftDeletes;

    protected $table = 'member';

    public function wallet()
    {
        return $this->hasOne(MemberWallet::class, 'member_id');
    }

    public function agent()
    {
        return $this->hasOne(Agent::class, 'id', 'agent_id');
    }

    public function level()
    {
        return $this->hasOne(Level::class, 'id', 'level_id');
    }

    public function rebate()
    {
        return $this->hasOne(Rebate::class, 'id', 'rebate_id');
    }

    public function tagMapping()
    {
        return $this->hasMany(MemberTagMapping::class, 'member_id');
    }

    public function distinctLoginLog()
    {
        return $this->hasMany(MemberLoginLog::class, 'member_id')->selectRaw('distinct(`ip`)');
    }

    public function latestLoginLog()
    {
        return $this->hasOne(MemberLoginLog::class, 'member_id')->latest();
    }

    public function loginIpExists()
    {
        $ip_arr = $this->distinctLoginLog->map(function ($row) {
            return $row->ip;
        })->toArray();

        /** @var User */
        $user = auth()->user();
        $limit_agent_id_arr = $user->isAgent() ? $user->agent->children()->map(function ($row) {
            return $row->id;
        }) : [];
        $limit_user_id_arr = $user->isAgent() ? $user->agent->children()->map(function ($row) {
            return $row->user_id;
        }) : [];

        $id = $this->id;
        $ip_str = '"' . implode('", "', $ip_arr) . '"';
        $limit_user_id_str = '"' . implode('", "', $limit_user_id_arr) . '"';
        $limit_agent_id_str = '"' . implode('", "', $limit_agent_id_arr) . '"';
        $user_login_ip_exists = "SELECT * FROM `user_login_log` WHERE `ip` IN ($ip_str) " . ($limit_user_id_str ? "AND `user_id` IN ($limit_user_id_str) " : '') . "LIMIT 1";
        $member_login_ip_exists = "SELECT * FROM `member_login_log` WHERE `member_id` != $id AND `ip` IN ($ip_str) " . ($limit_agent_id_str ? "AND `member_id` IN (SELECT `id` FROM `member` WHERE `agent_id` IN ($limit_agent_id_str)) " : '') . "LIMIT 1";
        $member_register_ip_exists = "SELECT * FROM `member` WHERE `id` != $id AND `register_ip` IN ($ip_str) " . ($limit_agent_id_str ? "AND `agent_id` IN ($limit_agent_id_str) " : '') . "LIMIT 1";
        $sql = "SELECT IF (EXISTS($user_login_ip_exists) OR EXISTS($member_login_ip_exists) OR EXISTS($member_register_ip_exists), 1, 0) AS `ip_exists`";

        return DB::select($sql)[0]->ip_exists;
    }

    public function registerIpExists()
    {
        /** @var User */
        $user = auth()->user();
        $limit_agent_id_arr = $user->isAgent() ? $user->agent->children()->map(function ($row) {
            return $row->id;
        }) : [];
        $limit_user_id_arr = $user->isAgent() ? $user->agent->children()->map(function ($row) {
            return $row->user_id;
        }) : [];

        $id = $this->id;
        $register_ip = $this->register_ip;
        $limit_user_id_str = '"' . implode('", "', $limit_user_id_arr) . '"';
        $limit_agent_id_str = '"' . implode('", "', $limit_agent_id_arr) . '"';
        $user_login_ip_exists = "SELECT * FROM `user_login_log` WHERE `ip` = '$register_ip' " . ($limit_user_id_str ? "AND `user_id` IN ($limit_user_id_str) " : '') . "LIMIT 1";
        $member_login_ip_exists = "SELECT * FROM `member_login_log` WHERE `member_id` != $id AND `ip` = '$register_ip' " . ($limit_agent_id_str ? "AND `member_id` IN (SELECT `id` FROM `member` WHERE `agent_id` IN ($limit_agent_id_str)) " : '') . "LIMIT 1";
        $member_register_ip_exists = "SELECT * FROM `member` WHERE `id` != $id AND `register_ip` = '$register_ip' " . ($limit_agent_id_str ? "AND `agent_id` IN ($limit_agent_id_str) " : '') . "LIMIT 1";
        $sql = "SELECT IF (EXISTS($user_login_ip_exists) OR EXISTS($member_login_ip_exists) OR EXISTS($member_register_ip_exists), 1, 0) AS `ip_exists`";

        return DB::select($sql)[0]->ip_exists;
    }

    public function getDeletedAtAttribute()
    {
        return $this->attributes['deleted_at'];
    }

    public function generatorInvitationCode()
    {
        $this->invitation_code = 'M' . mb_substr(sha1($this->username), 0, 5);
    }

    public function generatePassword()
    {
        $characters = '0123456789';
        $password = substr(str_shuffle($characters), rand(0, 9), 8);
        $this->password = Hash::make($password);

        return $password;
    }
}
