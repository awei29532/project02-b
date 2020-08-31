<?php

namespace App\Models;

use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $type
 * @property string $remark
 * @property string $start_at
 * @property string $end_at
 * @property string $maintainable_type
 * @property int $maintainable_id
 * @property int $status
 * @property string $updated_at
 * @property string $created_at
 */
class GameCompanyMaintenance extends BaseModel
{
    const REGEXCRONTAB = '/^(\d|[0-5]\d) (\d|[0-1]\d|[2][0-3]) \* \* [1-7](,[1-7]){0,6}$/';

    protected $table = 'game_company_maintenance';

    protected $fillable = [
        'remark',
        'start_at',
        'end_at',
        'status',
        'type',
        'maintainable_id',
        'maintainable_type',
    ];

    public $hidden = [
        'maintainable_id',
        'maintainable_type',
        'created_at',
        'updated_at'
    ];

    public function getStartFormatAttribute()
    {
        if(preg_match(self::REGEXCRONTAB, $this->start_at)){
            list($min, $hour) = explode(' ', $this->start_at);

            return Carbon::createFromTime($hour, $min)->format('H:i');
        }

        return $this->start_at;
    }

    public function getEndFormatAttribute()
    {
        if(preg_match(self::REGEXCRONTAB, $this->end_at)){
            list($min, $hour) = explode(' ', $this->end_at);

            return Carbon::createFromTime($hour, $min)->format('H:i');
        }

        return $this->end_at;
    }

    public function getWeeklyAttribute()
    {
        if(preg_match(self::REGEXCRONTAB, $this->end_at)){
            list(,,,,$weekly) = explode(' ', $this->end_at);
            if(preg_match('/,/i', $weekly))
                $weekly = explode(',', $weekly);

            if(is_string($weekly))
                $weekly = [$weekly];

            return $weekly;
        }

        return [];
    }

    public function GameCompany()
    {
        return $this->morphTo();
    }
}
