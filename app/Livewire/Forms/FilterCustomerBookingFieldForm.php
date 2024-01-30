<?php

namespace App\Livewire\Forms;

use Carbon\Carbon;
use Closure;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class FilterCustomerBookingFieldForm extends Form
{
  use WithFileUploads;

  public $day;
  public $date_from;
  public $date_until;
  public $field;

  public function rules()
  {
    return [
      'date_from' => ['nullable', 'date'],
      'date_until' =>  [
        'nullable', 'date', function (string $attribute, mixed $value, Closure $fail) {
          $carbonDateFrom = Carbon::parse($this->date_from);
          $carbonDateUntil = Carbon::parse($this->date_until);

          if ($carbonDateUntil->lessThan($carbonDateFrom)) {
            $fail("Date Until must not be less than date from !");
          }
        }
      ],
      // 'field' =>  ''
    ];
  }

  public function setFilter()
  {
    $this->reset();
    $this->date_from = todayDate();
    $this->date_until = Carbon::parse(todayDate())->addDays(7)->toDateString();
  }
}
