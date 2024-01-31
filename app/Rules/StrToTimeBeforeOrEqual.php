<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;


class StrToTimeBeforeOrEqual implements ValidationRule
{
    protected $strtime;

    public function __construct(string $strtime)
    {
        $this->strtime = $strtime;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $endTime = addOneDayIfPastMidnight(formatToDateTime($value));
        $startTime = addOneDayIfPastMidnight(formatToDateTime($this->strtime));

        $carbonStartTime =  Carbon::parse($startTime);
        $carbonEndTime =  Carbon::parse($endTime);

        if ($carbonEndTime->lessThanOrEqualTo($carbonStartTime)) {
            $fail("The $attribute must be after than start time !");
        }
    }
}
