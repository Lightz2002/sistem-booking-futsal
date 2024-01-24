<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;
use App\Models\Package;

class EditPackageForm extends Form
{
    use WithFileUploads;
    public ?Package $package;


    public $id;

    public $name = '';
    public $image;

    public function setPackage(Package $package)
    {
        $this->id = $package->id;
        $this->name = $package->name;
        $this->image = $package->image;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'min:3', Rule::unique('packages')->ignore($this->id)],
            'image' =>  ''
        ];
    }
}
