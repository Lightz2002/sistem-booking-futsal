<x-modal name="add-package-detail">
  <form wire:submit.prevent="addPackageDetail" class="p-6 text-gray-800" enctype="multipart/form-data">

      <h2 class="text-lg font-medium text-gray-900">
          {{ __('Add Package Detail') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
          {{ __('') }}
      </p>

      <div class="mt-6">
          <x-input-label for="start_time" value="{{ __('Start Time') }}"  />

          <x-text-input
              model="addPackageDetailForm.start_time"
              id="start_time"
              type="time"
              name="start_time"
              class="mt-1 block w-3/4"
              placeholder="{{ __('Start Time') }}"
          />

          <x-input-error :messages="$errors->get('addPackageDetailForm.start_time')" class="mt-2" />
      </div>

      <div class="mt-6">
          <x-input-label for="end_time" value="{{ __('End Time') }}"  />

          <x-text-input
              model="addPackageDetailForm.end_time"
              id="end_time"
              type="time"
              name="end_time"
              class="mt-1 block w-3/4"
              placeholder="{{ __('End Time') }}"
          />

          <x-input-error :messages="$errors->get('addPackageDetailForm.end_time')" class="mt-2" />
      </div>

      <div class="mt-6">
          <x-input-label for="price" value="{{ __('Price') }}"  />

          <x-text-input
              model="addPackageDetailForm.price"
              type="text"
              id="price"
              name="price"
              class="mt-1 block w-3/4"
              placeholder="{{ __('Price') }}"
          />

          <x-input-error :messages="$errors->get('addPackageDetailForm.price')" class="mt-2" />
      </div>

      <div>
          <x-input-error :messages="$errors->get('addPackageDetailForm.package_id')" class="mt-2" />
      </div>

      <div class="mt-6 flex justify-end">
          <x-secondary-button x-on:click="$dispatch('close');">
              {{ __('Cancel') }}
          </x-secondary-button>

          <x-primary-button class="ms-3">
              {{ __('Save') }}
          </x-primary-button>
      </div>
  </form>
</x-modal>
