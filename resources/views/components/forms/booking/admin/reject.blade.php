<x-modal name="reject-booking-payment" :show="false">
  <form wire:submit.prevent="rejectBookingPayment" class="p-6">
      <h2 class="text-lg font-medium text-gray-900">
          {{ __('Are you sure you want to reject this booking payment ?') }}
          
      </h2>

      <p class="mt-1 text-sm text-gray-600">
        {{ __('Provide the reject reason for customer who booked this ') }}
      </p>

      <div class="my-4">
        <x-input-label for="reject_reason" value="{{ __('Reason') }}"  />

          <x-text-input
            wire:model.live="rejectBookingForm.reject_reason"
              id="reject_reason"
              name="reject_reason"
              class="mt-1 block w-3/4"
              placeholder="{{ __('Reason') }}"
          />
      </div>

      <div class="mt-6 flex justify-end">
          <x-secondary-button x-on:click="$dispatch('close'); ">
              {{ __('Cancel') }}
          </x-secondary-button>

          <x-primary-button class="ms-3">
              {{ __('Reject') }}
          </x-primary-button>
      </div>
  </form>
</x-modal>