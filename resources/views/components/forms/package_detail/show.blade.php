@props([
  'packageDetail',
  'package'
])

<x-modal name="view-package-detail">
  <div class="bg-white rounded-md ">
      <div class="flex items-center p-4 bg-indigo-600 text-white  mb-6">
          <h3 class="font-bold text-xl">View Package Detail</h3>

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
                @if ($this->package->status !== 'confirmed')

                  <x-dropdown-button
                  x-on:click.prevent="$dispatch('open-modal', 'edit-package-detail')"
                  >
                      {{ __('Edit') }}
                  </x-dropdown-button>
                  <x-dropdown-button
                  x-on:click.prevent="$dispatch('open-modal', 'delete-package-detail')"
                  >
                      {{ __('Delete') }}
                  </x-dropdown-button>
                @else
                <x-dropdown-button
                >
                    {{ __('No Actions Available') }}
                </x-dropdown-button>
                @endif
              </x-slot>
          </x-dropdown>
      </div>

      <div class="grid sm:grid-cols-2 gap-8 py-4 px-8">
          <x-detail-desc label="Start Time" :value="$packageDetail->start_time"/>
          <x-detail-desc label="End Time" :value="$packageDetail->end_time"/>
          <x-detail-desc label="Price" :value="formatToRupiah($packageDetail->price)"/>
      </div>
  </div>
</x-modal>
