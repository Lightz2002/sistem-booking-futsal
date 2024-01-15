<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use App\Models\Field;

new class extends Component {
    use WithFileUploads;

    public bool $showAddModal = false;

    #[Rule('required|min:3')]
    public string $name = '';

    #[Rule('file|max:1024')]
    public $image;

    public function addField() {
        if (isset($this->image) && !empty($this->image)) {
            $fileName = $this->image->getClientOriginalName();

            $imageName = now()->timestamp . '_' . $fileName;
            $imagePath = $this->image->storeAs('img', $imageName, 'public');

            $this->image = 'storage/' . $imagePath;
        }

        Field::create([
            'name' => $this->name,
            'image' => $this->image ?? '',
        ]);

        $this->dispatch('close-modal', 'add-field');
        Session::flash('message', 'Field Added Successfully');
        $this->dispatch('open-alert', name: 'success-alert', type: 'success');
    }

}; ?>

<div class="h-full  rounded-md mx-auto p-8">
    <x-alert type="success" name="success-alert"></x-alert>

    <div class="flex items-center mb-4">
        <h1 class="font-bold text-2xl">Fields</h1>
        <x-primary-button class="ml-auto"
        x-on:click.prevent="$dispatch('open-modal', 'add-field')"
        >Add</x-primary-button>
    </div>

    <div class="flex items-center w-1/3 relative">
            <input wire:model="search" name="search" type="search" placeholder="Search..."
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="stroke-slate-400 w-6 h-6 absolute top-1/2 right-10 translate-y-[-50%]">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
    </div>

    <x-modal name="add-field" :show="$showAddModal" focusable>
        <form wire:submit.prevent="addField" class="p-6" enctype="multipart/form-data">

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Add Field') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('') }}
            </p>

            <div class="mt-6">
                <x-input-label for="name" value="{{ __('Name') }}"  />

                <x-text-input
                    wire:model.blur="name"
                    id="name"
                    name="name"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Name') }}"
                />

                <x-input-error :messages="$errors->get('name')" class="mt-2" />
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
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Save') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</div>
