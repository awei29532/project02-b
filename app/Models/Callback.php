<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $site_id
 * @property int $member_id
 * @property string $phone
 * @property string $ip
 * @property int $status
 * @property int $cs_id
 * @property string $callback_at
 * @property string $remark
 * @property string $created_at
 * @property string $updated_at
 */
class Callback extends BaseModel
{
    protected $table = 'callback';

    protected $fillable = [
        'id',
        'site_id',
        'member_id',
        'phone',
        'ip',
        'status',
        'remark',
        'created_at',
        'updated_at',
    ];

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id')->withTrashed();
    }

    public function cs()
    {
        return $this->hasOne(CustomerService::class, 'id', 'cs_id');
    }
}
