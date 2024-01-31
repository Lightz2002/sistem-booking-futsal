<?php

namespace App\Livewire\Forms;

use App\Models\Field;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Package;
use App\Models\PackageDetail;
use Carbon\Carbon;
use Closure;
use Livewire\WithFileUploads;

class AddPackageForm extends Form
{
    use WithFileUploads;

    #[Validate('required')]
    public string $code = '';

    #[Validate('required')]
    public string $name = '';

    public string $valid_end = '';

    #[Validate('required|exists:fields,name')]
    public string $field = '';

    #[Validate('file|max:1024|nullable')]
    public $image;

    public function rules()
    {
        return [
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

    public function store($fieldModel)
    {
        if (isset($this->image) && !empty($this->image)) {
            $fileName = $this->image->getClientOriginalName();

            $imageName = now()->timestamp . '_' . $fileName;
            $imagePath = $this->image->storeAs('img', $imageName, 'public');

            $this->image = 'storage/' . $imagePath;
        }

        Package::create([
            'name' => $this->name,
            'code' => $this->code,
            'valid_end' => $this->valid_end,
            'field_id' => $fieldModel->id,
            'image' => $this->image ?? '',
        ]);
    }
}
