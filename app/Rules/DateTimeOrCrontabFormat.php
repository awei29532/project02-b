<?php

namespace App\Rules;

use App\Rules\DateTime as BaseDateTime;

class DateTimeOrCrontabFormat extends BaseDateTime
{
    const REGEXCRONTAB = '/^(\d|[0-5]\d) (\d|[0-1]\d|[2][0-3]) \* \* [1-7](,[1-7]){0,6}$/';

    public function passes($attribute, $value)
    {
        if (!preg_match(self::REGEXCRONTAB, $value))
            return $this->validateDate($value);

        return true;
    }

    public function message()
    {
        return __('validation.date_format');
    }
}
