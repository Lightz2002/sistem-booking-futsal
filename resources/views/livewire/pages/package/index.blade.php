<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use App\Models\Field;
use App\Models\Package;
use Livewire\Attributes\Url;
use App\Livewire\Forms\AddPackageForm;

new class extends Component {

    use WithFileUploads, WithPagination;

    public AddPackageForm $addPackageForm;

    public function with(): array
    {
        return [
            'fields' => Field::filter($this->addPackageForm->field)->select('name')->get(),
            'packages' => Package::filter($this->search, $this->status)->get(),
            'status' => $this->status,
        ];
    }

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'status')]
    public $status = 'verifying';

    public function searchPackages() {
        $this->resetPage();
    }

    public function addPackage() {
        $this->addPackageForm->validate(); 

        $fieldModel = Field::firstWhere('name', $this->addPackageForm->field);

        if (!$fieldModel) {
            return $this->dispatch('open-alert', name: 'success-alert', type: 'error', message: 'Field does not exist !');
        }

        $this->addPackageForm->store($fieldModel);

        $this->dispatch('close-modal', 'add-package');
        $this->dispatch('open-alert', name: 'success-alert', type: 'Success', message: 'Package Added Successfully');
        $this->addPackageForm->reset();
    }

    public function redirectToDetail($id) {
        $this->redirectRoute('packages.detail', ['package' => $id]);
    }

    public function filterStatus($status) {
        $this->status = $status;
    }
}
//

?>

<div class="h-full  rounded-md mx-auto">
    <x-alert name="success-alert"></x-alert>

    <div class="flex items-center mb-4">
        <h1 class="font-bold text-2xl">Packages</h1>
        <x-primary-button class="ml-auto"
        x-on:click.prevent="$dispatch('open-modal', 'add-package')"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>

            <span>Add Package</span>
        </x-primary-button>
    </div>

    <x-status-bar :statuses="['verifying', 'confirmed']" :selectedStatus="$status"></x-status-bar>
    <x-search model="search" search="searchPackages" />

    {{-- create form --}}
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
                    model="addPackageForm.code"
                    id="code"
                    name="code"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Code') }}"
                />

                <x-input-error :messages="$errors->get('addPackageForm.code')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="name" value="{{ __('Name') }}"  />

                <x-text-input
                    model="addPackageForm.name"
                    id="name"
                    name="name"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Name') }}"
                />

                <x-input-error :messages="$errors->get('addPackageForm.name')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="valid_end" value="{{ __('Valid End') }}"  />

                <x-text-input
                    model="addPackageForm.valid_end"
                    type="date"
                    id="valid_end"
                    name="valid_end"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Valid End') }}"
                />

                <x-input-error :messages="$errors->get('addPackageForm.valid_end')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="field" value="{{ __('Field') }}"  />

                <x-select
                    model="addPackageForm.field"
                    :options="$fields"
                    :data="['id' => 'selectField2', 'name' => 'field']"
                />

                <x-input-error :messages="$errors->get('addPackageForm.field')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="image" value="{{ __('Image') }}"  />

                <x-text-input
                    wire:model="addPackageForm.image"
                    id="image"
                    name="image"
                    type="file"
                    accept="image/*"
                    class="my-1 block w-3/4"
                    placeholder="{{ __('Image') }}"
                />

                <x-input-error :messages="$errors->get('addPackageForm.image')" class="mt-2" />

                <div wire:loading wire:target='image' class="bg-indigo-600 text-white mt-2 animate-pulse w-3/4 px-4 py-1 rounded-full max-h-6  text-sm">Uploading...</div>
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

    {{-- list --}}
    <div class="flex gap-4 flex-col sm:flex-row mb-2">
        @foreach($packages as $package)
        <div class="w-full pb-2 sm:w-56 overflow-hidden bg-white rounded-lg  hover:cursor-pointer hover:transition-all transition-transform transform hover:scale-105 shadow-lg dark:bg-gray-800"
        wire:key="{{ $package->id }}"
        wire:click="redirectToDetail({{ $package->id }})"
        >
            <img class="object-cover w-full h-48" src="{{ $package->image }}" alt="{{ $package->name }}">
            <div class="flex items-center justify-between px-4 py-2 bg-gray-900">
                <h1 class="text-lg font-bold text-white">{{ $package->name }}</h1>
            </div>

            <h3 class="px-4 text-md font-bold text-gray-400">{{ ucwords($package->field->name) }}</h3>

        </div>
        @endforeach
    </div>
</div>