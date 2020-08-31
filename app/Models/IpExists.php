<?php

namespace App\Models;

/**
 * @property string $ip
 * @property string $updated_at
 * @property string $created_at
 */
class IpExists extends BaseModel
{
    protected $table = 'ip_exists';

    protected $primaryKey = 'ip';

    protected $incrementing = false;

    const UPDATEDAT = 'last_used';
}
