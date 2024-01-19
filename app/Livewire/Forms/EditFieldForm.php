<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class EditFieldForm extends Form
{
    use WithFileUploads;

    public $name = '';
    public $image;


    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'image' =>  ''
        ];
    }
}
