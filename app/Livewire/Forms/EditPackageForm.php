<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;
use App\Models\Package;
use Illuminate\Support\Facades\Storage;

class EditPackageForm extends Form
{
    use WithFileUploads;
    public ?Package $package;

    public $id;

    public $code = '';

    #[Validate('required')]
    public $name = '';
    public $valid_end = '';
    public $status = '';

    #[Validate('file|max:1024|nullable')]
    public $image;

    public function setPackage(Package $package)
    {
        $this->id = $package->id;
        $this->code = $package->code;
        $this->name = $package->name;
        $this->valid_end = $package->valid_end;
        $this->status = $package->status;
    }

    public function rules()
    {
        return [
            'code' => ['required',  Rule::unique('packages')->ignore($this->id)],
            'image' =>  'file|nullable'
        ];
    }

    public function update($package)
    {
        if ($this->image) {
            $trimmedImagePath = str_replace('storage/', '', $package->image);
            Storage::disk('public')->delete($trimmedImagePath);

            $fileName = $this->image->getClientOriginalName();

            $imageName = now()->timestamp . '_' . $fileName;
            $imagePath = $this->image->storeAs('img', $imageName, 'public');

            $this->image = 'storage/' . $imagePath;
        } else {
            $this->image = $package->image;
        }

        $package->update(
            $this->all()
        );

        $this->reset('image');
    }
}
