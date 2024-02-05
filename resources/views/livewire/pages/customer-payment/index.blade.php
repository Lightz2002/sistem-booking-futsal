<?php

use Carbon\Carbon;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use App\Models\Field;
use App\Models\Package;
use App\Models\Allotment;
use App\Models\Payment;
use Livewire\Attributes\Url;
use App\Livewire\Forms\CustomerPaymentForm;

new class extends Component {
  use WithFileUploads;

  public CustomerPaymentForm $customerPaymentForm;


  public function with(): array {
    return [
      'allotments' => Allotment::with(['field'])->where('user_id', auth()->user()->id)
                      ->where('status', 'hold')->paginate(5),
    ];
  }

  public function removeBooking($bookingId) {
    Allotment::where('id', $bookingId)->update([
      'status' => 'available',
      'user_id' => null
    ]);

    $this->dispatch('booking-updated');
    return $this->redirectRoute('customer-payments');
  }

  public function redirectBackToBooking() {
    $lastAllotmentBeforePayment = Allotment::where('user_id', auth()->user()->id)
    ->where('status', 'hold')
    ->orderBy('updated_at', 'desc')
    ->first();

    if (!$lastAllotmentBeforePayment) {
      $this->redirectRoute('customer-bookings');
      return;
    }

    $this->redirectRoute('customer-bookings.field-detail', ['field' => $lastAllotmentBeforePayment->field_id]);
  }

  public function payBooking() {
    try { 
      
      $this->customerPaymentForm->validate();

      if (isset($this->customerPaymentForm->payment_proof) && !empty($this->customerPaymentForm->payment_proof)) {
            $fileName = $this->customerPaymentForm->payment_proof->getClientOriginalName();

            $imageName = now()->timestamp . '_' . $fileName;
            $imagePath = $this->customerPaymentForm->payment_proof->storeAs('img', $imageName, 'public');

            $this->customerPaymentForm->payment_proof = 'storage/' . $imagePath;
        } else if (empty($this->customerPaymentForm->payment_proof)) {
          throw new Exception('If you have uploaded payment proof, Wait a while before submitting the form !');
        }

        $allotmentsForPay = Allotment::where('user_id', auth()->user()->id)
        ->whereNull('payment_id')
        ->where('status', 'hold');


        $payment = Payment::create([
            'payment_proof' => $this->customerPaymentForm->payment_proof ?? '',
            'total_payment' => $allotmentsForPay->get()->sum('price')
        ]);

        $allotmentsForPay->update([
          'status' => 'verifying',
          'payment_id' => $payment->id
        ]);

      $this->dispatch('open-alert', name: 'payment-alert', message: 'Booking paid successfully and will be checked !');
      $this->redirectRoute('customer-upcoming-bookings');
    } catch (Exception $e) {
        $this->dispatch('open-alert', name: 'payment-alert', type: 'Error', message: $e->getMessage());
    }
  }
}
//

?>

<div>
  <x-alert name="payment-alert"></x-alert>
  <div class="w-full md:px-8  md:max-w-1/3 mx-auto">
    <div class="rounded  pt-6 pb-8 mb-4">
      <!-- Step Indicator -->
      <div  wire:ignore class="flex items-center justify-center mb-6 step-indicator">
        <div class="step-1 w-6 h-6 rounded-full flex items-center justify-center bg-indigo-600 text-white step-1-indicator">1</div>
        <div class="step-1 w-1/3 h-1 bg-indigo-600"></div>
        <div class="step-2 w-1/3 h-1 bg-gray-300"></div>
        <div class="step-2 w-6 h-6 rounded-full flex items-center justify-center bg-gray-300">2</div>
      </div>
  
      <!-- Step 1: Booking List -->
      <div wire:ignore id="step-1" class="mb-4">
        <button wire:click="redirectBackToBooking" class="my-4 flex items-center border border-indigo-600 text-indigo-600 rounded-md px-4 py-2 ">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 me-2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
          </svg>
          
          <span>Back</span>
        </button>

        <div class="text-center">
          <h2 class="text-3xl font-bold mb-2 ">Please Confirm The Booking</h2>
          <h5 class="text-gray-500">Make sure the booking list is correct</h5>
        </div>

        <div class="flex flex-col rounded-md my-4 gap-4  p-4">
          @foreach($allotments as $allotment)
            @php
                $formattedDate = Carbon::parse($allotment->date)->format('d-M-Y');
                $formattedStartTime = Carbon::createFromTimeString($allotment->start_time)->format('H:i');
                $formattedEndTime = Carbon::createFromTimeString($allotment->end_time)->format('H:i');
            @endphp

            <div class="bg-white px-4 py-4 relative rounded-md">
              <div class="flex items-center">
                <div>
                  <h3 class="text-sm md:text-lg font-bold text-black">{{ $allotment->field->name }}</h3>
                  <h5 class="text-sm md:text-md font-bold text-gray-600">
                    <span class="me-2">{{ $formattedDate }}</span>
                    <span>{{ $formattedStartTime . ' - ' . $formattedEndTime }}</span>
                  </h5>
                </div>

                <div class="flex flex-col justify-center items-center ml-auto">
                  <h3 class=" text-black text-xl md:text-2xl font-bold mb-2">{{  formatToRupiah($allotment->price) }}</h3>

                  <div wire:click="removeBooking('{{ $allotment->id }}')" class="flex items-center text-red-400 hover:cursor-pointer border border-red-400 px-4 py-2 rounded-md text-sm md:text-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 md:w-6 md:h-6 me-2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>

                    <span>Delete</span>
                  </div>
                </div>
              </div>

            </div>
          @endforeach

          <div class="bg-white p-4 rounded-md flex items-baseline justify-end">
            <h3 class="text-black text-lg md:text-xl me-4">Total</h3>
            <h3 class="text-black text-xl md:text-2xl font-bold mb-2">{{  formatToRupiah($allotments->sum('price')) }}</h3>
          </div>
        </div>
      </div>

      <!-- Step 2: Payment -->
      <div  wire:ignore id="step-2" class="hidden mb-4">
        <button class="my-4 flex items-center prev-btn border border-indigo-600 text-indigo-600 rounded-md px-4 py-2 ">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 me-2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
          </svg>

          <span>Back</span>
        </button>

        <div class="text-center">
          <h2 class="text-3xl font-bold mb-2 ">Payment</h2>
          <h5 class="text-gray-500 mb-2">Double check the payment to avoid scam</h5>
        </div>

        <div class="mt-6 mx-auto flex content-center items-center gap-2">
          <div class="w-1/2">
            <img src="https://perbankansyariah.umsida.ac.id/wp-content/uploads/2022/05/QRIS-MASJID-AL-HIKMAH.jpeg" alt="" class="w-52 h-52 mx-auto object-cover">
            
            <div class="bg-white p-4 my-4 rounded-md flex items-baseline justify-end">
              <h3 class="text-black text-lg md:text-xl me-4">Total</h3>
              <h3 class="text-black text-xl md:text-2xl font-bold mb-2">{{  formatToRupiah($allotments->sum('price')) }}</h3>
            </div>
          </div>

          <div>
            <x-input-label for="payment_proof" value="{{ __('Payment Proof') }}"  />
            <x-text-input
                model="customerPaymentForm.payment_proof"
                id="payment_proof"
                name="payment_proof"
                type="file"
                accept="image/*"
                class="my-1 block w-3/4"
                placeholder="{{ __('Payment Proof') }}"
            />
  
            <x-input-error :messages="$errors->get('customerPaymentForm.payment_proof')" class="mt-2" />
  
            <div wire:loading wire:target='payment_proof' class="bg-indigo-600 text-white mt-2 animate-pulse w-3/4 px-4 py-1 rounded-full max-h-6  text-sm">Uploading...</div>
          </div>
        </div>
      </div>

    </div>
  </div>

  @if (count($allotments) > 0)
  <div  wire:ignore class="next-btn bg-white p-4 sticky bottom-0 ">
    <button class="w-full py-4 text-center bg-indigo-600 rounded-md  text-white hover:-translate-y-2 transition-all">Confirm Booking</button>
  </div>
  
  <div wire:ignore wire:click="payBooking" class="hidden payment-btn bg-white p-4 sticky bottom-0 ">
    <button class="w-full py-4 text-center bg-indigo-600 rounded-md  text-white hover:-translate-y-2 transition-all">I Have Transferred</button>
  </div>
  @endif
  

  <script>
    $(document).ready(function() {
      // Initial step
      let currentStep = 1;
      let lastStep = 2;
      
      // Show the current step and hide the rest
      function showStep(step) {
        $('[id^="step-"]').addClass('hidden');
        $('#step-' + step).removeClass('hidden');
        
        // Update the step indicator
        $('.step-indicator *').removeClass('!bg-indigo-600 bg-indigo-600 !text-white text-white');
        $('.step-indicator *').addClass('bg-gray-300 text-black');
        $('.step-indicator .step-' + step ).addClass('!bg-indigo-600 !text-white');
      }
      
      // Next step
      $('.next-btn').click(function() {
        if (currentStep < 3) {
          currentStep++;
          showStep(currentStep);
        }

        if (currentStep === lastStep ) {
          $('.next-btn').addClass('hidden');
          $('.payment-btn').removeClass('hidden');
        }
      });
      
      // Previous step
      $('.prev-btn').click(function() {
        if (currentStep > 1) {
          currentStep--;
          showStep(currentStep);

          $('.payment-btn').addClass('hidden');
          $('.next-btn').removeClass('hidden');
        }
      });
    });
    </script>