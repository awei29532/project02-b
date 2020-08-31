<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $site_id
 * @property string $content
 * @property int $status
 * @property string $send_at
 */
class Sms extends BaseModel
{
    protected $table = 'sms';

    public $timestamps = false;
}
