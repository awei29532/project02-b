<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $member_id
 * @property string $device
 * @property string $browser
 * @property string $ip
 * @property string $updated_at
 * @property string $created_at
 */
class MemberLoginLog extends BaseModel
{
    protected $table = 'member_login_log';

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }

    public function sameIpExists($ip, $member_id)
    {
        $user_login_log = "SELECT * FROM `user_login_log` WHERE `ip` = '$ip' " . "LIMIT 1";
        $member_login_log = "SELECT * FROM `member` WHERE `register_ip` = '$ip' AND `member_id` != '$member_id' LIMIT 1";
        $member_register_log = "SELECT * FROM `member_login_log` WHERE `ip` = '$ip' AND `id` != '$member_id' LIMIT 1";
        $sql = "SELECT IF (EXISTS($user_login_log) OR EXISTS($member_login_log) OR EXISTS($member_register_log), 1, 0) AS `ip_exists`";
        return DB::select($sql)[0]->ip_exists;
    }
}
