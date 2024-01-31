<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class EditFieldForm extends Form
{
    use WithFileUploads;

    public $id;
    public $name = '';
    public $image;


    public function rules()
    {
        return [
            'name' => ['required', 'min:2', Rule::unique('fields')->ignore($this->id)],
            'image' =>  ''
        ];
    }
}
