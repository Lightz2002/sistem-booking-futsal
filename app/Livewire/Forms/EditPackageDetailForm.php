<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Package;
use App\Models\PackageDetail;
use App\Rules\ExistPackageTime;
use App\Rules\StrToTimeBeforeOrEqual;
use Carbon\Carbon;
use Closure;

class   EditPackageDetailForm extends Form
{

  public ?PackageDetail $packageDetail;

  public $id;
  public $package_id;
  public $start_time = '';
  public $end_time = '';
  public $price = 1000;

  public function rules()
  {
    return [
      'package_id' => ['required', 'exists:packages,id'],
      'start_time' => [
        'required',
        function (string $attribute, mixed $value, Closure $fail) {
          $formattedTime = addOneDayIfPastMidnight(formatToDateTime($value));

          $sqlStartTimePastMidnight = sqlStartTimePastMidnight();
          $sqlEndTimePastMidnight = sqlEndTimePastMidnight();

          $existStartTime = PackageDetail::whereRaw("$sqlStartTimePastMidnight <= ?", [$formattedTime])
            ->whereRaw("$sqlEndTimePastMidnight > ?", [$formattedTime])
            ->where("package_id", [$this->package_id])
            ->whereNot('id', $this->id)
            ->first();

          if ($existStartTime) {
            $fail("There is already package detail within this time !");
          }
        }
      ],
      'end_time' => [
        'required', new StrToTimeBeforeOrEqual($this->start_time),
        function (string $attribute, mixed $value, Closure $fail) {
          $today = todayDate();
          $formattedEndTime = addOneDayIfPastMidnight(formatToDateTime($value));
          $formattedStartTime = addOneDayIfPastMidnight(formatToDateTime($this->start_time));

          $sqlStartTimePastMidnight = sqlStartTimePastMidnight();
          $sqlEndTimePastMidnight = sqlEndTimePastMidnight();

          $existEndTime = PackageDetail::whereRaw("$sqlStartTimePastMidnight < ?", [$formattedEndTime])
            ->whereRaw("$sqlEndTimePastMidnight >= ?", [$value])
            ->where("package_id", [$this->package_id])
            ->where('id', '!=', $this->id)
            ->first();

          $existTime = PackageDetail::whereRaw("$sqlStartTimePastMidnight >= ?", [$formattedStartTime])
            ->whereRaw("$sqlEndTimePastMidnight <= ?", [$formattedEndTime])
            ->where("package_id", [$this->package_id])
            ->where('id', '!=', $this->id)
            ->first();

          if ($existEndTime || $existTime) {
            $fail("There is already package detail within this time !");
          }
        }
      ],
      'price' => ['required', 'numeric', 'gte:1000']
    ];
  }

  public function setPackageDetail(PackageDetail $packageDetail)
  {
    $carbonStartTime  = Carbon::createFromTimeString($packageDetail->start_time);
    $carbonEndTime = Carbon::createFromTimeString($packageDetail->end_time);

    $this->id = $packageDetail->id;
    $this->package_id = $packageDetail->package_id;
    $this->start_time = $carbonStartTime->format('H:i');
    $this->end_time =  $carbonEndTime->format('H:i');
    $this->price = $packageDetail->price;
  }

  public function update(PackageDetail $packageDetail)
  {
    $packageDetail->update($this->all());
  }
}
