@props([
  'packageDetail'
])

<x-modal name="edit-package-detail">
    <div class="bg-white rounded-md ">
        <form wire:submit.prevent="editPackageDetail" class="p-6">

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Edit Package Detail') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('') }}
            </p>

            <div class="mt-6">
                <x-input-label for="start_time" value="{{ __('Start Time') }}"  />

                <x-text-input
                    wire:model.blur="editPackageDetailForm.start_time"
                    id="start_time"
                    name="start_time"
                    type="time"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Start Time') }}"
                />

                <x-input-error :messages="$errors->get('editPackageDetailForm.start_time')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="end_time" value="{{ __('End Time') }}"  />

                <x-text-input
                    wire:model.blur="editPackageDetailForm.end_time"
                    id="end_time"
                    name="end_time"
                    type="time"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('End Time') }}"
                />

                <x-input-error :messages="$errors->get('editPackageDetailForm.end_time')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="price" value="{{ __('Price') }}"  />

                <x-text-input
                    wire:model.blur="editPackageDetailForm.price"
                    id="price"
                    name="price"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Price') }}"
                />

                <x-input-error :messages="$errors->get('editPackageDetailForm.price')" class="mt-2" />
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
    </div>
</x-modal>