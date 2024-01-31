
<?php 
    $days = json_decode(json_encode([
        [
            'id' => 'Monday',
            'name' => 'Monday'
        ],
        [
            'id' => 'Tuesday',
            'name' => 'Tuesday'
        ],
        [
            'id' => 'Wednesday',
            'name' => 'Wednesday'
        ],
        [
            'id' => 'Thursday',
            'name' => 'Thursday'
        ],
        [
            'id' => 'Friday',
            'name' => 'Friday'
        ],
        [
            'id' => 'Saturday',
            'name' => 'Saturday'
        ],
        [
            'id' => 'Sunday',
            'name' => 'Sunday'
        ], 
    ]));
?>

<x-modal name="filter-field-customer-booking" :show="false">
  <form wire:submit.prevent="filterBookingFields" class="p-6 text-gray-800" enctype="multipart/form-data">

      <h2 class="text-lg font-medium text-gray-900">
          {{ __('Filter Booking') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
          {{ __('') }}
      </p>


      <div class="mt-6">
          <x-input-label for="date_from" value="{{ __('Date From') }}"  />

          <x-text-input
            wire:model.live="filterCustomerBookingFieldForm.date_from"
              id="date_from"
              name="date_from"
              type="date"
              class="mt-1 block w-3/4"
              placeholder="{{ __('Date From') }}"
          />

          <x-input-error :messages="$errors->get('filterCustomerBookingFieldForm.date_from')" class="mt-2" />
      </div>

      <div class="mt-6">
          <x-input-label for="date_until" value="{{ __('Date Until') }}"  />

          <x-text-input
              wire:model.live="filterCustomerBookingFieldForm.date_until"
              id="date_until"
              name="date_until"
              type="date"
              class="mt-1 block w-3/4"
              placeholder="{{ __('Date Until') }}"
          />

          <x-input-error :messages="$errors->get('filterCustomerBookingFieldForm.date_until')" class="mt-2" />
      </div>

      <div class="mt-6">
          <x-input-label for="field" value="{{ __('Field') }}"  />

          <x-select
              model="filterCustomerBookingFieldForm.field"
              :options="$fieldAutoCompletes"
              :data="['id' => 'field', 'name' => 'field']"
          />

          <x-input-error :messages="$errors->get('filterCustomerBookingFieldForm.field')" class="mt-2" />
      </div>


      <div class="mt-6 flex justify-end">
          <x-secondary-button wire:click="resetFilter" x-on:click="$dispatch('close');$dispatch('close-autocomplete');">
              {{ __('Cancel') }}
          </x-secondary-button>

          <x-primary-button class="ms-3">
              {{ __('Filter') }}
          </x-primary-button>
      </div>
  </form>
</x-modal>