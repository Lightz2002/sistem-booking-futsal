<?php

use Livewire\Volt\Component;
use App\Models\Field;
use App\Livewire\Forms\EditFieldForm;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public EditFieldForm $form;

    public $isEdit = false;

    public $field;

    public function mount(Field $field) {
        $this->field = $field;
        $this->form->name = $this->field->name;
    }

    public function update() {
        $this->form->validate();

        if ($this->form->image) {
            $trimmedImagePath = str_replace('storage/', '', $this->field->image);
            Storage::disk('public')->delete($trimmedImagePath);

            $fileName = $this->form->image->getClientOriginalName();

            $imageName = now()->timestamp . '_' . $fileName;
            $imagePath = $this->form->image->storeAs('img', $imageName, 'public');

            $this->form->image = 'storage/' . $imagePath;
        } else {
            $this->form->image = $this->field->image;
        }


        $this->field->update(
            $this->form->all()
        );

        $this->dispatch('open-alert', name: 'success-alert', type: 'success');
        $this->form->reset();
        $this->form->name = $this->field->name;
    }

}; ?>

<div class="">
    <x-alert type="success" name="success-alert"></x-alert>

    <div class="flex items-center mb-4">
        <h1 class="font-bold text-2xl">View Field</h1>

        <x-dropdown class="ml-auto" >
            <x-slot name="trigger">
                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <div>Actions</div>

                    <div class="ms-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-dropdown-link>
            </x-slot>
        </x-dropdown>
    </div>

    <form wire:submit="update" action="" class=" block md:flex bg-white rounded-md py-10 px-6" enctype="multipart/form-data">
        <div class="rounded-md md:w-1/2 h-80 md:flex-shrink-0 mb-6">
            <img src="{{ asset($field->image) }}" alt="{{ $field->name }}"
            class="h-full w-full object-cover mb-4">
    
            @if($isEdit)
                <x-text-input :readonly="!$isEdit" id="image" name="image" model="form.image" type="file" class="{{ $isEdit ? '' : 'bg-transparent border-none p-0 shadow-none' }} md:text-lg"   />
                <x-input-error :messages="$errors->get('form.image')" class="mt-2" />
            @endif
        </div>

        <div class="product-detail md:ml-4  grid grid-cols-2 justify-start justify-self-start content-start gap-4">
            <h2 class="text-gray-200 dark:text-gray-800 text-lg md:text-xl  mb-4 font-bold col-span-2">Field Details</h2>

            <div>
                <x-input-label for="name" :value="__('Name')" class="text-gray-200 dark:text-gray-800 md:text-lg"/>
                <x-text-input :readonly="!$isEdit" id="name" name="name" model="form.name" class="{{ $isEdit ? '' : 'bg-transparent border-none p-0 shadow-none' }} md:text-lg"   />
                <x-input-error :messages="$errors->get('form.name')" class="mt-2" />
            </div>

            <div class="flex items-center col-span-2 gap-4 ml-auto">
                <x-secondary-button wire:click="$toggle('isEdit')" type="button">
                    {{ $isEdit ? __('Cancel') : __('Edit') }}
                </x-secondary-button>

                @if($isEdit)
                    <x-primary-button>
                    {{ __('Save') }}
                    </x-primary-button>
                @endif
            </div>
        </div>
    </form>
</div>