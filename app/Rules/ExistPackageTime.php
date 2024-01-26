<?php

namespace App\Rules;

use App\Models\PackageDetail;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistPackageTime implements ValidationRule
{
    protected $packageId;

    public function __construct(string $packageId)
    {
        $this->packageId = $packageId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Now you can access $this->startTime and $this->endTime within your validation logic.
        // For example, you can compare $value against these start and end times.

        // Sample validation logic (adjust according to your requirements):
        $existPackageTime = PackageDetail::whereRaw("start_time <= CONCAT(?, ':00')", [$value])
            ->whereRaw("end_time >= CONCAT(?, ':00')", [$value])
            ->where("package_id", [$this->packageId])
            ->first();


        if ($existPackageTime) {
            // Validation failed
            $fail("There is already package detail within time $value !");
        }
        //
    }
}
