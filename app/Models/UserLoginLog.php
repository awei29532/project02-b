<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int $user_id
 * @property string $device
 * @property string $browser
 * @property string $ip
 * @property string $updated_at
 * @property string $created_at
 */
class UserLoginLog extends BaseModel
{
    protected $table = 'user_login_log';

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function sameIpExists($ip, $user_id)
    {
        $user_login_log = "SELECT * FROM `user_login_log` WHERE `ip` = '$ip' AND `user_id` != '$user_id' LIMIT 1";
        $member_login_log = "SELECT * FROM `member` WHERE `register_ip` = '$ip' LIMIT 1";
        $member_register_log = "SELECT * FROM `member_login_log` WHERE `ip` = '$ip' LIMIT 1";
        $sql = "SELECT IF (EXISTS($user_login_log) OR EXISTS($member_login_log) OR EXISTS($member_register_log), 1, 0) AS `ip_exists`";
        return DB::select($sql)[0]->ip_exists;
    }
}
