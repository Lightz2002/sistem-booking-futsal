<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use App\Models\Field;
use App\Models\Package;

new class extends Component {

    use WithFileUploads, WithPagination;

    public function with(): array
    {
        return [
            'fields' => Field::filter($this->field)->select('name')->get()
        ];
    }

    #[Validate('required')]
    public string $code = '';

    #[Validate('required')]
    public string $name = '';

    #[Validate('required|date')]
    public string $valid_end = '';

    #[Validate('required|exists:fields,name')]
    public string $field = '';

    #[Validate('file|max:1024|nullable')]
    public $image;

    public $search = '';

    public function addPackage() {
        $this->validate(); 


        $fieldModel = Field::firstWhere('name', $this->field);

        if (!$fieldModel) {
            return $this->dispatch('open-alert', name: 'success-alert', type: 'error', message: 'Field does not exist !');
        }

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

        $this->dispatch('close-modal', 'add-package');
        $this->dispatch('open-alert', name: 'success-alert', type: 'success', message: 'Package Added Successfully');
        $this->reset();
    }
}
//

?>

<div class="h-full  rounded-md mx-auto">
    <x-alert type="success" name="success-alert"></x-alert>

    <div class="flex items-center mb-4">
        <h1 class="font-bold text-2xl">Packages</h1>
        <x-primary-button class="ml-auto"
        x-on:click.prevent="$dispatch('open-modal', 'add-package')"
        >Add</x-primary-button>
    </div>

    <div class="flex items-center w-1/3 relative mb-4">
        <input wire:model.live="search" wire:keydown="searchFields" name="search" type="search" placeholder="Search..."
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="stroke-slate-400 w-6 h-6 absolute top-1/2 right-10 translate-y-[-50%]">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
        </svg>
    </div>

    <x-modal name="add-package" :show="false" focusable>
        <form wire:submit.prevent="addPackage" class="p-6" enctype="multipart/form-data">

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Add Package') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('') }}
            </p>

            <div class="mt-6">
                <x-input-label for="code" value="{{ __('Code') }}"  />

                <x-text-input
                    model="code"
                    id="code"
                    name="code"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Code') }}"
                />

                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="name" value="{{ __('Name') }}"  />

                <x-text-input
                    model="name"
                    id="name"
                    name="name"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Name') }}"
                />

                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="valid_end" value="{{ __('Valid End') }}"  />

                <x-text-input
                    model="valid_end"
                    type="date"
                    id="valid_end"
                    name="valid_end"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Valid End') }}"
                />

                <x-input-error :messages="$errors->get('valid_end')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="field" value="{{ __('Field') }}"  />

                <x-select
                    model="field"
                    :options="$fields"
                    :data="['id' => 'selectField2', 'name' => 'field']"
                />

                <x-input-error :messages="$errors->get('field')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="image" value="{{ __('Image') }}"  />

                <x-text-input
                    wire:model="image"
                    id="image"
                    name="image"
                    type="file"
                    accept="image/*"
                    class="my-1 block w-3/4"
                    placeholder="{{ __('Image') }}"
                />


                <progress wire:loading wire:target="image" class="bg-indigo-600 progress w-56"></progress>

                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close');$dispatch('close-autocomplete');">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Save') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</div>