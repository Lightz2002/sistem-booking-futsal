<?php

use Livewire\Volt\Component;
use App\Models\Allotment;

new class extends Component {

    public $booking;
    public $totalBookingPrice;

    public function mount(Allotment $booking) {
        $this->booking = $booking;
        $this->totalBookingPrice = Allotment::where('payment_id', $booking->payment_id)->get()->sum('price');
    }

}; ?>

<div class="">
    <x-alert name="success-alert"></x-alert>

    <h1 class="mb-4 font-bold text-2xl">View Rejected Booking</h1>

    <x-tabs>
        <x-slot name="content">
            <div class="bg-white p-8 rounded-md">
                <div x-cloak  x-show="activeTab === 0" class="rounded-r-md md:flex gap-4">
                        <div class="rounded-md h-80 md:flex-shrink-0 mb-6">
                            <img src="{{ asset($booking->payment->payment_proof) }}" alt="Payment Proof"
                            class="h-full w-full object-cover mb-4 border border-white">
                        </div>

                    <div class="grid grid-cols-2 gap-8 content-start ">
                        <x-detail-desc label="Reject Reason" :value="$booking->payment->reject_reason" />

                        {{-- payment ini total harga, bukan jumlah yg dibyr user, slh bkin wkwk --}}
                        <x-detail-desc label="Total Price" :value="formatToRupiah($booking->payment->total_payment)" />
                    </div>
                </div>
            </div>
        </x-slot>
    </x-tabs>

</div>