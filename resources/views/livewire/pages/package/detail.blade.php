<?php

use Livewire\Volt\Component;
use App\Models\Package;
use App\Models\PackageDetail;
use App\Livewire\Forms\EditPackageForm;
use Livewire\WithPagination;


new class extends Component {
    use WithPagination;

    public EditPackageForm $form;

    public $isEdit = false;

    public $package;
    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'asc';

    public function mount(Package $package) {
        $this->form->setPackage($package);
        $this->package = $package;
    }

    public function with(): array
    {
        $packageDetails =  PackageDetail::filter($this->search)
        ->where('package_id', $this->package->id)
        ->orderBy($this->sortBy, $this->sortDirection)
        ->paginate(1);

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

    function sort($key) {
        $this->resetPage();

        if ($this->sortBy === $key) {
            $direction = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            $this->sortDirection = $direction;

            return;
        }

        $this->sortBy = $key;
        $this->sortDirection = 'asc';
    }

}

//

?>

<div class="">
    <x-alert name="success-alert"></x-alert>   

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
                        <x-search model="search" search="searchPackageDetails" class="text-gray-800"/>

                        <x-table :details="$details" :detailColumns="$detailColumns" :sortBy="$sortBy"
                        :sortDirection="$sortDirection"/>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-tabs>

</div>