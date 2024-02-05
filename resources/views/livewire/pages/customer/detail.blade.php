<?php

use Livewire\Volt\Component;
use App\Models\User;

new class extends Component {

    public $customer;

    public function mount(User $customer) {
        $this->customer = $customer;
    }

}; ?>

<div class="">
    <x-alert name="success-alert"></x-alert>

    <h1 class="mb-4 font-bold text-2xl">View Customers</h1>

    <x-tabs>
        <x-slot name="content">
            <div class="bg-white p-8 rounded-md">
                <div x-cloak  x-show="activeTab === 0" class="rounded-r-md grid sm:grid-cols-2 gap-4">
                    <div class="grid grid-cols-2 gap-8 content-start ">
                        <x-detail-desc label="Name" :value="$customer->name" />
                        <x-detail-desc label="Email" :value="$customer->email" />
                        <x-detail-desc label="Phone No" :value="$customer->phone_no" />
                        <x-detail-desc label="Address" :value="$customer->address" />
                    </div>
                </div>
            </div>
        </x-slot>
    </x-tabs>

</div>