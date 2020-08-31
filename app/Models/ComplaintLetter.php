<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $site_id
 * @property int $member_id
 * @property string $title
 * @property string content
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class ComplaintLetter extends BaseModel
{
    protected $table = 'complaint_letter';

    protected $fillable = [
        'id',
        'site_id',
        'member_id',
        'title',
        'ontent',
        'status',
        'created_at',
        'updated_at',
    ];

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id')->withTrashed();
    }
}
