<?php

namespace App\Livewire\Forms;

use App\Models\Field;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;
use App\Models\Package;
use Closure;
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
    #[Validate('required|exists:fields,name')]
    public $field = '';
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
        $this->field = $package->field->name;
    }

    public function rules()
    {
        return [
            'code' => ['required',  Rule::unique('packages')->ignore($this->id)],
            'image' =>  'file|nullable',
            'valid_end' => [
                'required', 'date', function (string $attribute, mixed $value, Closure $fail) {
                    $existPackageValidEnd = Package::where('packages.status', 'confirmed')
                        ->leftJoin('fields as f', 'f.id', '=', 'packages.field_id')
                        ->whereRaw("DATE_FORMAT(valid_end, '%Y-%m-%d') >= ?", [$value])
                        ->where('f.name', $this->field)
                        ->first();

                    if ($existPackageValidEnd) {
                        $fail("There is already package for this field and date !");
                    }
                }
            ]
        ];
    }

    public function update($package, $fieldModel)
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


        $package->update([
            'name' => $this->name,
            'code' => $this->code,
            'valid_end' => $this->valid_end,
            'field_id' => $fieldModel->id,
            'image' => $this->image ?? '',
        ]);

        $this->reset('image');
    }
}
