<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class CustomerPaymentForm extends Form
{
    use WithFileUploads;

    #[Validate('file|max:1024|required')]
    public $payment_proof;
}
