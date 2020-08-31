<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $site_id
 * @property string $member_ids
 * @property string $title
 * @property string $content
 * @property int $status
 * @property string $send_at
 */
class Message extends BaseModel
{
    protected $table = 'message';

    public $timestamps = false;
}
