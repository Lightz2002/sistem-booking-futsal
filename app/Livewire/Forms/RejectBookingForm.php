<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class RejectBookingForm extends Form
{
    use WithFileUploads;

    #[Validate('required')]
    public $reject_reason = '';
}
