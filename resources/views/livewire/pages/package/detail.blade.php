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

    public function mount(Package $package) {
        // $this->form = new EditPackageForm(); // Instantiate the form
        $this->form->setPackage($package);
        $this->package = $package;
    }

    public function with(): array
    {
        $packageDetails =  PackageDetail::filter($this->search)
        ->where('package_id', $this->package->id
        )->paginate(2);

        return [
            'package' => $this->package,
            'details' => $packageDetails,
            'detailColumns' => [
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
            ]
        ];
    }

    public function searchPackageDetails() {
        $this->resetPage();
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

                        <table class="table-auto w-full  text-left  border-slate-200 mb-2">
                            <thead>
                                <tr>
                                    @foreach ($detailColumns as $column)
                                        <th
                                            class="p-3 border-b  border-slate-200 bg-slate-200 text-slate-500 cursor-pointer">
                                            <div class="flex">
                                                {{ $column['label'] }}
                                                {{-- @if ($sortBy === $column->key)
                                                    @if ($sortDirection === 'asc')
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                @endif --}}
                                            </div>
                                        </th>
                                    @endforeach
                
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details as $row)
                                    <tr>
                                        @foreach ($detailColumns as $column)
                                            <td class="p-3 border-b cursor-pointer bg-white text-gray-800 border-slate-200">
                                                {{-- <div class="py-3 px-6 text-gray-800 flex items-center cursor-pointer"> --}}
                                                {{-- <x-dynamic-component :id="$row['id']" :component="$column['component']" :value="$row[$column['key']]" :row="$row">
                                                </x-dynamic-component> --}}
                                                {{ $row[$column['key']] }}
                                                {{-- </div> --}}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        {{ $details->withQueryString()->links()  }}

                    </div>
                </div>
            </div>
        </x-slot>
    </x-tabs>

</div>