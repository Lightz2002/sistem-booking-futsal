<?php

namespace App\Livewire\Forms;

use Carbon\Carbon;
use Closure;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class FilterCustomerRejectedBookingForm extends Form
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
      'date_from' => ['nullable', 'date'],
      'date_until' =>  ['nullable', 'date'],
    ];
  }

  public function setFilter()
  {
    $this->reset();
    $this->date_from = Carbon::parse(todayDate())->subDays(7)->toDateString();
    $this->date_until = Carbon::parse(todayDate())->subDays(1)->toDateString();
  }
}
