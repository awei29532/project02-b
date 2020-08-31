<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $user_id
 * @property int $level
 * @property string $line_id
 * @property string $line_qrcode
 * @property string $updated_at
 * @property string $created_at
 */
class CustomerService extends BaseModel
{
    protected $table = 'customer_service';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
