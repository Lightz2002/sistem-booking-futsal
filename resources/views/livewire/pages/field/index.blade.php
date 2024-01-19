<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use App\Models\Field;
use Illuminate\Pagination\LengthAwarePaginator;

new class extends Component {

    use WithFileUploads, WithPagination;

    #[Rule('required|min:3')]
    public string $name = '';

    #[Rule('file|max:1024')]
    public $image;

    public $search = '';


    public function with(): array
    {
        return [
            'fields' => Field::filter($this->search)->paginate(5)
        ];
    }


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

    public function searchFields() {
        $this->resetPage();
    }

    public function redirectToDetail($fieldId) {
        $this->redirectRoute('fields.detail', ['field' => $fieldId]);
    }

}; ?>

<div class="h-full  rounded-md mx-auto">
    <x-alert type="success" name="success-alert"></x-alert>

    <div class="flex items-center mb-4">
        <h1 class="font-bold text-2xl">Fields</h1>
        <x-primary-button class="ml-auto"
        x-on:click.prevent="$dispatch('open-modal', 'add-field')"
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

    <div class="flex gap-4 flex-col sm:flex-row mb-2">
        @foreach($fields as $field)
        <div class="w-full sm:w-56 overflow-hidden bg-white rounded-lg  hover:cursor-pointer hover:transition-all transition-transform transform hover:scale-105 shadow-lg dark:bg-gray-800"
        wire:key="{{ $field->id }}"
        wire:click="redirectToDetail({{ $field->id }})"
        >
            <img class="object-cover w-full h-48" src="{{ $field->image }}" alt="NIKE AIR">
            <div class="flex items-center justify-between px-4 py-2 bg-gray-900">
                <h1 class="text-lg font-bold text-white">{{ $field->name }}</h1>
                {{-- <button class="px-2 py-1 text-xs font-semibold text-gray-900 uppercase transition-colors duration-300 transform bg-white rounded hover:bg-gray-200 focus:bg-gray-400 focus:outline-none">Add to cart</button> --}}
            </div>

            {{-- <div class="px-4 py-2">
                <h1 class="text-xl font-bold text-gray-800 uppercase dark:text-white">{{ $field->name }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400"></p>
            </div> --}}
        </div>
        @endforeach
    </div>

    {{ $fields ? $fields->withQueryString()->links() : '' }}

    <x-modal name="add-field" :show="false" focusable>
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

    {{-- <x-modal class="p-4" name="detail-field" :show="false">
        <div class="flex items-center p-4 w-full mb-2">
            <h3 class="text-lg font-bold">Field</h3>
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

        <img class="w-full h-52 object-cover mb-2" src="{{ url('storage/img/1705207142_alfamart7.jpeg') }}" alt="">
    </x-modal> --}}
</div>
