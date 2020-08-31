<?php

namespace App\Rules;

use App\Rules\DateTime as BaseDateTime;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DateTimeOrCrontabRange extends BaseDateTime
{
    const REGEXCRONTAB = '/^(\d|[0-5]\d) (\d|[0-1]\d|[2][0-3]) \* \* [1-7](,[1-7]){0,6}$/';

    protected $params;

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    public function passes($attribute, $value)
    {
        try {
            $column = Str::before($attribute, 'end_at');
            $startAt = Arr::get($this->params, $column . "start_at");

            if (preg_match(self::REGEXCRONTAB, $value)) {
                list($startAtmin, $startAthour) = explode(" ", $startAt);
                list($endAtmin, $endAthour) = explode(" ", $value);

                if (Carbon::createFromTime($startAthour, $startAtmin)->gte(Carbon::createFromTime($endAthour, $endAtmin))) {
                    return false; // 請確認時間區間是否正確
                }
            } else {
                if (Carbon::parse($startAt)->gte(Carbon::parse($value))) {
                    return false; // 請確認時間區間是否正確
                }
            }
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return __('validation.after');
    }
}
