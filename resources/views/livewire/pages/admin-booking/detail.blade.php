<?php

use Carbon\Carbon;
use Livewire\Volt\Component;
use App\Models\Allotment;
use App\Livewire\Forms\RejectBookingForm;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;


new class extends Component {
  public Allotment $allotment;
  public RejectBookingForm $rejectBookingForm;

  public function mount(Allotment $allotment) {
        $this->allotment = $allotment;
  }

  public function with() {
    return [
      'allotment' => $this->allotment
    ];
  }

  public function confirmBookingPayment() {
    try {
      DB::transaction(function () {
          $this->allotment->update([
            'status' => 'confirmed'
        ]);

        $this->dispatch('close-modal', 'confirm-booking-payment');
        $this->dispatch('open-alert', name: 'booking-payment-alert', type: 'Success', message: 'Payment Confirmed And Booking Generated Successfully');

        $this->redirectRoute('admin-bookings');
      });
    } catch (Exception $e) {
        $this->dispatch('open-alert', name: 'booking-payment-alert', type: 'Error', message: $e->getMessage());
      }
  }

  public function rejectBookingPayment() {
    try {
      DB::transaction(function () {
        $this->rejectBookingForm->validate();
        
        $this->allotment->update([
            'status' => 'rejected'
        ]);


        $this->allotment->payment->update([
            'reject_reason' => $this->rejectBookingForm->reject_reason
        ]);

        $this->dispatch('close-modal', 'reject-booking-payment');
        $this->dispatch('open-alert', name: 'booking-payment-alert', type: 'Success', message: 'Payment Rejected Successfully');

        $this->redirectRoute('admin-bookings');
      });
    } catch (Exception $e) {
        $this->dispatch('open-alert', name: 'booking-payment-alert', type: 'Error', message: $e->getMessage());
      }
  }
}

?>

<div class="">
  <x-alert name="booking-payment-alert"></x-alert>

  <h1 class="mb-4 font-bold text-2xl">View Booking Details</h1>

  <x-tabs :tabs="['Main']">

    @if ($allotment->status === 'verifying')
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
              x-on:click.prevent="$dispatch('open-modal', 'confirm-booking-payment')"
              >
                  {{ __('Confirm') }}
              </x-dropdown-button>
              <x-dropdown-button
              x-on:click.prevent="$dispatch('open-modal', 'reject-booking-payment')"
              >
                  {{ __('Reject') }}
              </x-dropdown-button>
          </x-slot>
      </x-dropdown>
    </x-slot>
    @endif

    <x-slot name="content">
      <div class="bg-white p-8 rounded-md">
          <div x-cloak  x-show="activeTab === 0" class="rounded-r-md grid sm:grid-cols-2 gap-4">
              <div class="rounded-md h-80 md:flex-shrink-0 mb-6">
                  <a href="{{ url($allotment->payment->payment_proof) }}" target="_blank" class="hover:cursor-pointer">
                    <img src="{{ asset($allotment->payment->payment_proof) }}" alt="Payment Proof"
                      class="h-full w-full object-cover mb-4 border border-white">
                    </a>  

                  <h3 class="text-lg font-bold">Payment Proof</h3>
              </div>
  
              <div class="grid grid-cols-2 gap-8 content-start ">
                  <x-detail-desc label="Payment Date" :value="$allotment->payment->created_at" />
                  <x-detail-desc label="User" :value="$allotment->user->name" />
                  <x-detail-desc label="Phone No" :value="$allotment->user->phone_no" />
                  <x-detail-desc label="Booking Date" :value="$allotment->date" />
                  <x-detail-desc label="Start Time" :value="$allotment->start_time" />
                  <x-detail-desc label="End Time" :value="$allotment->end_time" />
                  <x-detail-desc label="Price" :value="formatToRupiah($allotment->price)" />
              </div>
          </div>
      </div>
  </x-slot>
  </x-tabs>

  <x-forms.booking.admin.confirm />
  <x-forms.booking.admin.reject />
</div>