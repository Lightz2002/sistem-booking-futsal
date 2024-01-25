<?php

namespace App\Rules;

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
        if (strtotime($value) <= strtotime($this->strtime)) {
            $fail("The $attribute must be after than start time !");
        }
    }
}
