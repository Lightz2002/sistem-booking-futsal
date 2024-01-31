<?php

namespace App\Livewire\Forms;

use Carbon\Carbon;
use Closure;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class FilterCustomerHistoryBookingForm extends Form
{
  use WithFileUploads;

  public $day;
  public $date_from;
  public $date_until;

  #[Validate('exists:fields,name|nullable')]
  public $field = '';

  public function rules()
  {
    return [
      'date_from' => ['nullable', 'date',  function (string $attribute, mixed $value, Closure $fail) {
        $carbonDateFrom = Carbon::parse($this->date_from);
        $today = Carbon::parse(todayDate());


        if ($carbonDateFrom->greaterThan($today)) {
          $fail("Date From must not be greater than today for history !");
        }
      }],
      'date_until' =>  [
        'nullable', 'date', function (string $attribute, mixed $value, Closure $fail) {
          $carbonDateFrom = Carbon::parse($this->date_from);
          $carbonDateUntil = Carbon::parse($this->date_until);
          $today = Carbon::parse(todayDate());

          if ($carbonDateUntil->lessThan($carbonDateFrom)) {
            $fail("Date Until must not be less than date from !");
          } else if ($carbonDateUntil->greaterThanOrEqualTo($today)) {
            $fail("Date Until must not be greater or equal to today date for history !");
          }
        }
      ],
    ];
  }

  public function setFilter()
  {
    $this->reset();
    $this->date_from = Carbon::parse(todayDate())->subDays(7)->toDateString();
    $this->date_until = Carbon::parse(todayDate())->subDays(1)->toDateString();
  }
}
