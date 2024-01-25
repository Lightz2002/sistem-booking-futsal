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
    <x-alert name="alert"></x-alert>   

    <h1 class="mb-4 font-bold text-2xl">View Packages</h1>

    <x-tabs :tabs="['Main', 'Detail']">
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

</div>