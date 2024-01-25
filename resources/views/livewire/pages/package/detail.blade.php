<?php

use Livewire\Volt\Component;
use App\Models\Package;
use App\Models\PackageDetail;
use App\Livewire\Forms\EditPackageForm;
use App\Livewire\Forms\addPackageDetailForm;
use Livewire\WithPagination;


new class extends Component {
    use WithPagination;

    public EditPackageForm $editPackageForm;
    public AddPackageDetailForm $addPackageDetailForm;

    public $isEdit = false;

    public $package;
    public $search = '';
    public $sortBy = 'start_time';
    public $sortDirection = 'asc';

    public function mount(Package $package) {
        $this->editPackageForm->setPackage($package);
        $this->addPackageDetailForm->setPackageId($package->id);
        $this->package = $package;
    }

    public function with(): array
    {
        $packageDetails =  PackageDetail::filter($this->search)
        ->where('package_id', $this->package->id)
        ->orderBy($this->sortBy, $this->sortDirection)
        ->paginate(5);

        $packageDetailColumns = [
                [
                    'key' => 'start_time',
                    'label' => 'Start Time'
                ],
                [
                    'key' => 'end_time',
                    'label' => 'End Time'
                ],
                [
                    'key' => 'price',
                    'label' => 'Price'
                ]
        ];

        return [
            'package' => $this->package,
            'details' => $packageDetails,
            'detailColumns' => $packageDetailColumns
        ];
    }

    public function editPackage() {
        $this->editPackageForm->validate();

        $this->editPackageForm->update($this->package);

        $this->dispatch('close-modal', 'edit-package');
        $this->dispatch('open-alert', name: 'edit-package-alert', type: 'Success', message: 'Package Updated Successfully !');

    }

    public function searchPackageDetails() {
        $this->resetPage();
    }

    public function sort($key) {
        $this->resetPage();

        if ($this->sortBy === $key) {
            $direction = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            $this->sortDirection = $direction;

            return;
        }

        $this->sortBy = $key;
        $this->sortDirection = 'asc';
    }

    public function addPackageDetails() {
        $this->addPackageDetailForm->validate();

        $this->addPackageDetailForm->store();

        $this->addPackageDetailForm->reset();
        $this->addPackageDetailForm->setPackageId($this->package->id);

        $this->dispatch('open-alert', name: 'add-detail-alert');
        $this->dispatch('close-modal', 'add-package-detail');
    }

}

//

?>

<div class="">
    <x-alert name="add-detail-alert"></x-alert>   
    <x-alert name="edit-package-alert"></x-alert>

    <h1 class="mb-4 font-bold text-2xl">View Packages</h1>

    <x-tabs :tabs="['Main', 'Detail']">
        <x-slot name="dropdown">
            <x-dropdown class="ml-auto" >
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md bg-gray-800 text-gray-300 hover:text-gray-100 focus:outline-none transition ease-in-out duration-150 " >
                        <div>Actions</div>

                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-button
                    x-on:click.prevent="$dispatch('open-modal', 'edit-package')"
                    >
                        {{ __('Edit') }}
                    </x-dropdown-button>
                </x-slot>
            </x-dropdown>
        </x-slot>

        <x-slot name="content">
            <div class="bg-gray-800 text-white p-8 rounded-md">
                <div  x-show="activeTab === 0" class="rounded-r-md grid sm:grid-cols-2 gap-4">
                    <div class="rounded-md h-80 md:flex-shrink-0 mb-6">
                        <img src="{{ asset($package->image) }}" alt="{{ $package->name }}"
                        class="h-full w-full object-cover mb-4 border border-white">
                    </div>
        
                    <div class="grid grid-cols-2 gap-8 content-start ">
                        <x-detail-desc label="Code" :value="$package->code" />
                        <x-detail-desc label="Name" :value="$package->name" />
                        <x-detail-desc label="Valid End" :value="$package->valid_end" />
                        <x-detail-desc label="Status" :value="$package->status" />
                    </div>
                </div>

                <div x-show="activeTab === 1" >
                    <h3 class="font-bold text-lg mb-4">Detail List</h3>
                    <div class="mb-4  overflow-hidden rounded-lg shadow-md">

                        <x-search model="search" search="searchPackageDetails" class="text-gray-800">
                            <x-primary-button class="ml-auto bg-indigo-600 hover:bg-indigo-400"
                            x-on:click.prevent="$dispatch('open-modal', 'add-package-detail')"
                            >Add</x-primary-button>
                        </x-search>

                        <x-table :details="$details" :detailColumns="$detailColumns" :sortBy="$sortBy"
                        :sortDirection="$sortDirection"/>

                        <x-modal name="add-package-detail">
                            <form wire:submit.prevent="addPackageDetails" class="p-6 text-gray-800" enctype="multipart/form-data">

                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Add Package Detail') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('') }}
                                </p>

                                <div class="mt-6">
                                    <x-input-label for="start_time" value="{{ __('Start Time') }}"  />

                                    <x-text-input
                                        model="addPackageDetailForm.start_time"
                                        id="start_time"
                                        type="time"
                                        name="start_time"
                                        class="mt-1 block w-3/4"
                                        placeholder="{{ __('Start Time') }}"
                                    />

                                    <x-input-error :messages="$errors->get('addPackageDetailForm.start_time')" class="mt-2" />
                                </div>

                                <div class="mt-6">
                                    <x-input-label for="end_time" value="{{ __('End Time') }}"  />

                                    <x-text-input
                                        model="addPackageDetailForm.end_time"
                                        id="end_time"
                                        type="time"
                                        name="end_time"
                                        class="mt-1 block w-3/4"
                                        placeholder="{{ __('End Time') }}"
                                    />

                                    <x-input-error :messages="$errors->get('addPackageDetailForm.end_time')" class="mt-2" />
                                </div>

                                <div class="mt-6">
                                    <x-input-label for="price" value="{{ __('Price') }}"  />

                                    <x-text-input
                                        model="addPackageDetailForm.price"
                                        type="text"
                                        id="price"
                                        name="price"
                                        class="mt-1 block w-3/4"
                                        placeholder="{{ __('Price') }}"
                                    />

                                    <x-input-error :messages="$errors->get('addPackageDetailForm.price')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-error :messages="$errors->get('addPackageDetailForm.package_id')" class="mt-2" />
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close');">
                                        {{ __('Cancel') }}
                                    </x-secondary-button>

                                    <x-primary-button class="ms-3">
                                        {{ __('Save') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </x-modal>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-tabs>

    <x-modal name="edit-package" :show="false">
        <form wire:submit.prevent="editPackage" class="p-6 text-gray-800" enctype="multipart/form-data">

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Edit Package') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('') }}
            </p>

            <div class="mt-6">
                <x-input-label for="code" value="{{ __('Start Time') }}"  />

                <x-text-input
                    model="editPackageForm.code"
                    id="code"
                    name="code"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Code') }}"
                />

                <x-input-error :messages="$errors->get('editPackageForm.code')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="name" value="{{ __('Name') }}"  />

                <x-text-input
                    model="editPackageForm.name"
                    id="name"
                    name="name"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Name') }}"
                />

                <x-input-error :messages="$errors->get('editPackageForm.name')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="valid_end" value="{{ __('Valid End') }}"  />

                <x-text-input
                    model="editPackageForm.valid_end"
                    id="valid_end"
                    type="date"
                    name="valid_end"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Valid End') }}"
                />

                <x-input-error :messages="$errors->get('editPackageForm.valid_end')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="image" value="{{ __('Image') }}"  />

                <x-text-input
                    model="editPackageForm.image"
                    id="image"
                    type="file"
                    name="image"
                    accept="image/*"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Image') }}"
                />

                <x-input-error :messages="$errors->get('editPackageForm.image')" class="mt-2" />
            </div>

            <div>
                <x-input-error :messages="$errors->get('editPackageForm.id')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close');">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Save') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</div>