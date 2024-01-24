<?php

use Livewire\Volt\Component;
use App\Models\Package;
use App\Livewire\Forms\EditPackageForm;
use Livewire\WithPagination;


new class extends Component {
    use WithPagination;

    public EditPackageForm $form;

    public $isEdit = false;

    public $package;

    public function mount(Package $package) {
        // $this->form = new EditPackageForm(); // Instantiate the form
        $this->form->setPackage($package);
        $this->package = $package;
    }

    public function with(): array
    {
        return [
            'package' => $this->package,
        ];
    }
}

//

?>

<div class="">
    <x-alert name="success-alert"></x-alert>   

    <h1 class="mb-4 font-bold text-2xl">View Packages</h1>

    <x-tabs :tabs="['Main', 'Detail']">
        <x-slot name="content">
            <div  x-show="activeTab === 0" class="bg-gray-800 text-white p-8 rounded-md rounded-r-md grid sm:grid-cols-2 gap-4">
                <div class="rounded-md h-80 md:flex-shrink-0 mb-6">
                    <img src="{{ asset($package->image) }}" alt="{{ $package->name }}"
                    class="h-full w-full object-cover mb-4 border border-white">
                </div>
    
                <div class="grid grid-cols-2 gap-8 content-start">
                    <div>
                        <h3 class="font-bold text-lg">Code</h3>
                        <h3 class="text-md">{{ $package->code }}</h3>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Name</h3>
                        <h3 class="text-md">{{ $package->name }}</h3>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Valid End</h3>
                        <h3 class="text-md">{{ $package->valid_end }}</h3>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Status</h3>
                        <h3 class="text-md">{{ $package->status }}</h3>
                    </div>
                </div>
            </div>
            <div x-show="activeTab === 1">
                <h3>Detail</h3>
            </div>
        </x-slot>
    </x-tabs>

</div>