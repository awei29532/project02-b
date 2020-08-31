<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $user_id
 * @property int $extend_id
 * @property string $updated_at
 * @property string $created_at
 */
class SubAccount extends BaseModel
{
    protected $table = 'sub_account';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
        'extend_id'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'extend_id' =>'integer',
    ];

    public function mainAccount()
    {
        return $this->belongsTo(User::class, 'extend_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
