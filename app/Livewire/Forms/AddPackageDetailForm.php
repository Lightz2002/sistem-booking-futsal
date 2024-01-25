<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Package;
use App\Models\PackageDetail;
use App\Rules\ExistPackageTime;
use App\Rules\StrToTimeBeforeOrEqual;
use Closure;

class AddPackageDetailForm extends Form
{

    public $package_id;
    public $start_time = '';
    public $end_time = '';
    public $price = 1000;

    /* 
    1. udh ad jam  7 - 10, 9 - 10, 11 - 12
    2. mau tmbh start = 9 (g bole krna ad yg mulai start_time <=  9 dan end_time > 9  )
    3. mau tmbh end = 7 (g bole krna <= 9 )
    3. mau tmbh end = 10 (g bole krna ad yg startnya <= 10 dan end_time >= 10)
    */

    public function rules()
    {
        return [
            'package_id' => ['required', 'exists:packages,id'],
            'start_time' => [
                'required',
                function (string $attribute, mixed $value, Closure $fail) {
                    $existStartTime = PackageDetail::whereRaw("start_time <= CONCAT(?, ':00')", [$value])
                        ->whereRaw("end_time > CONCAT(?, ':00')", [$value])
                        ->where("package_id", [$this->package_id])
                        ->first();

                    if ($existStartTime) {
                        $fail("There is already package detail within this time !");
                    }
                }
            ],
            'end_time' => [
                'required', new StrToTimeBeforeOrEqual($this->start_time),
                function (string $attribute, mixed $value, Closure $fail) {
                    $existEndTime = PackageDetail::whereRaw("start_time < CONCAT(?, ':00')", [$value])
                        ->whereRaw("end_time >= CONCAT(?, ':00')", [$value])
                        ->where("package_id", [$this->package_id])
                        ->first();

                    $existTime = PackageDetail::whereRaw("start_time >= CONCAT(?, ':00')", [$this->start_time])
                        ->whereRaw("end_time <= CONCAT(?, ':00')", [$value])
                        ->first();

                    if ($existEndTime || $existTime) {
                        $fail("There is already package detail within this time !");
                    }
                }
            ],
            'price' => ['required', 'numeric', 'gte:1000']
        ];
    }

    public function setPackageId($package_id)
    {
        $this->package_id = $package_id;
    }

    public function store()
    {
        PackageDetail::create($this->all());
    }
}
