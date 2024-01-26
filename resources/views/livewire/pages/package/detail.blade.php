<?php

use Carbon\Carbon;
use Livewire\Volt\Component;
use App\Models\Package;
use App\Models\PackageDetail;
use App\Models\Allotment;
use App\Livewire\Forms\EditPackageForm;
use App\Livewire\Forms\addPackageDetailForm;
use App\Livewire\Forms\EditPackageDetailForm;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;


new class extends Component {
    use WithFileUploads, WithPagination;

    public EditPackageForm $editPackageForm;
    public AddPackageDetailForm $addPackageDetailForm;
    public EditPackageDetailForm $editPackageDetailForm;

    public $isEdit = false;

    public $package;
    public PackageDetail $packageDetail;
    
    #[Url(as: 'q')]
    public $search = '';
    #[Url(as: 'sort')]
    public $sortBy = 'start_time';
    #[Url(as: 'direction')]
    public $sortDirection = 'asc';

    public function mount(Package $package) {
        $this->editPackageForm->setPackage($package);
        $this->addPackageDetailForm->setPackageId($package->id);
        $this->package = $package;
        $this->packageDetail = new PackageDetail();
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
                    'label' => 'Price',
                    'component' => 'columns.package_detail.price'
                ],
                [
                    'key' => 'action',
                    'label' => 'Action',
                    'component' => 'columns.package_detail.action'
                ]
        ];

        return [
            'package' => $this->package,
            'packageDetail' => $this->packageDetail,
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

    public function deletePackage() {
        /* validation */
        // $packageDetails = PackageDetail::where('package_id', $this->package->id)->first();
        // if ($packageDetails) {
        //     $this->dispatch('open-alert', name: 'delete-package-alert', type: 'Error', message: 'Delete Package Details First !');
        //     return;
        // }

        try {
            if ($this->package->status === 'posted') {
                $this->dispatch('open-alert', name: 'delete-package-alert', type: 'Error', message: 'Package Already Posted !');
                return;
            }

            $package = $this->package->update(['status' => 'inactive']);

            if ($package) {
                $this->dispatch('close-modal', 'delete-package');
                $this->dispatch('open-alert', name: 'delete-package-alert', type: 'Success', message: 'Package Deleted Successfully !');
                $this->redirectRoute('packages');
            }
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function searchPackageDetails() {
        $this->resetPage();
    }

    public function sort($key) {
        if ($key === 'action') return;

        $this->resetPage();

        if ($this->sortBy === $key) {
            $direction = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            $this->sortDirection = $direction;

            return;
        }

        $this->sortBy = $key;
        $this->sortDirection = 'asc';
    }

    public function addPackageDetail() {
        $this->addPackageDetailForm->validate();

        $this->addPackageDetailForm->store();

        $this->addPackageDetailForm->reset();
        $this->addPackageDetailForm->setPackageId($this->package->id);

        $this->dispatch('open-alert', name: 'add-detail-alert');
        $this->dispatch('close-modal', 'add-package-detail');
    }

    public function viewPackageDetail($id)
    {
        $this->packageDetail = PackageDetail::firstWhere("id", $id);
        if ($this->packageDetail) {
            $this->editPackageDetailForm->setPackageDetail($this->packageDetail);
            $this->dispatch('open-modal', 'view-package-detail');
        }
    }

    public function editPackageDetail() {
        $this->editPackageDetailForm->validate();

        $this->editPackageDetailForm->update($this->packageDetail);

        $this->dispatch('close-modal', 'edit-package-detail');
        $this->dispatch('open-alert', name: 'edit-package-alert', type: 'Success', message: 'Package Updated Successfully !');

    }

    public function deletePackageDetail() {
        $this->packageDetail->delete();

        $this->dispatch('close-modal', 'view-package-detail');
        $this->dispatch('close-modal', 'delete-package-detail');
        $this->dispatch('open-alert', name: 'delete-package-detail-alert', type: 'Success', message: 'Package Detail Deleted Successfully !');
    }

    private function validateConfirmPackage() {
        $today = Carbon::parse(todayDate());
        $validEnd = Carbon::parse($this->package->valid_end);

        if ($validEnd->lessThan($today)) throw new Exception('Package valid end must be after today !');
    }

    private function generateAvailableAllotment(PackageDetail $detail, int $day) {
        return [
            'package_id' => $this->package->id,
            'field_id' => $this->package->field_id,
            'date' => Carbon::now()->addDays($day)->format('Y-m-d'),
            'start_time' => $detail->start_time,
            'end_time' => $detail->end_time,
            'price' => $detail->price,
            'status' => 'available',
            'created_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
            'updated_at' => Carbon::now('Asia/Jakarta')->toDateTimeString(),
        ];
    }

    public function confirmPackage() {
        try {
            $this->validateConfirmPackage();

            DB::transaction(function() {
                $today = todayDate();
                $validEnd = $this->package->valid_end;

                $totalValidAllotmentDays = Carbon::parse($today)->diffInDays(Carbon::parse($validEnd));

                $allotmentInstance = [];
                for ($day = 0; $day <= $totalValidAllotmentDays; $day++) {
                    foreach($this->package->package_details as $detail) {
                        $allotmentInstance[] = $this->generateAvailableAllotment($detail, $day);
                    }
                }

                DB::table('allotments')->insert($allotmentInstance);

                $this->package->update(['status' => 'confirmed']);
            });

            $this->dispatch('close-modal', 'confirm-package');
            $this->dispatch('open-alert', name: 'confirm-package-alert', type: 'Success', message: 'Package Confirmed Successfully !');
            $this->redirectRoute('packages');
        } catch (Exception $e) {
            $this->dispatch('close-modal', 'confirm-package');
            $this->dispatch('open-alert', name: 'confirm-package-alert', type: 'Error', message: $e->getMessage());
        }
    }
}

?>

<div class="">
    <x-alert name="edit-package-alert"></x-alert>
    <x-alert name="delete-package-alert"></x-alert>
    <x-alert name="confirm-package-alert"></x-alert>
    <x-alert name="add-detail-alert"></x-alert>
    <x-alert name="edit-package-detail-alert"></x-alert>
    <x-alert name="delete-package-detail-alert"></x-alert>

    <h1 class="mb-4 font-bold text-2xl">View Packages</h1>

    <x-tabs :tabs="['Main', 'Detail']">
        @if ($this->package->status !== 'confirmed')
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
                    <x-dropdown-button
                    x-on:click.prevent="$dispatch('open-modal', 'delete-package')"
                    >
                        {{ __('Delete') }}
                    </x-dropdown-button>
                    <x-dropdown-button
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-package')"
                    >
                        {{ __('Change To Confirmed') }}
                    </x-dropdown-button>
                </x-slot>
            </x-dropdown>
        </x-slot>
        @endif

        <x-slot name="content">
            <div class="bg-white p-8 rounded-md">
                <div x-cloak  x-show="activeTab === 0" class="rounded-r-md grid sm:grid-cols-2 gap-4">
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

                <div x-cloak x-show="activeTab === 1" >
                    <div class="flex items-center">
                        <h3 class="font-bold text-lg mb-4">Detail List</h3>

                        @if ($this->package->status !== 'confirmed')
                            <x-primary-button class="ml-auto bg-indigo-600 hover:bg-indigo-400"
                            x-on:click.prevent="$dispatch('open-modal', 'add-package-detail')"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Add Package Detail</span>    
                            </x-primary-button>
                        @endif
                    </div>

                    <x-search model="search" search="searchPackageDetails" class="text-gray-800" />

                    <x-table :details="$details" :detailColumns="$detailColumns" :sortBy="$sortBy"
                    :sortDirection="$sortDirection"/>

                    {{-- add package detail --}}
                    <x-forms.package_detail.add :packageDetail="$packageDetail"/>

                    {{-- view package detail --}}
                    <x-forms.package_detail.show :package="$package" :packageDetail="$packageDetail"/>

                    {{-- edit package detail --}}
                    <x-forms.package_detail.edit packageDetail="$packageDetail"/>

                    {{-- delete package detail --}}
                    <x-forms.package_detail.delete packageDetail="$packageDetail"/>
                </div>
            </div>
        </x-slot>
    </x-tabs>

   <x-forms.package.edit />
   <x-forms.package.delete />
   <x-forms.package.confirm />
</div>