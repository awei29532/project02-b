<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DateTime as BaseDateTime;

abstract class DateTime implements Rule
{
    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = BaseDateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
