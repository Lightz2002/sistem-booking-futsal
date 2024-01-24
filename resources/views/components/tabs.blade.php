@props([
    'tabs',
])

<div x-data="{
  tabs: @js($tabs),
  activeTab: 0 // Index of the initially active tab
}">

  <div class="flex mb-2 rounded-md  w-1/3">
    <template x-for="tab, index in tabs" :key="index">
      <button
      class="rounded-md bg-white text-gray-800 px-8 py-2 hover:bg-gray-200 hover:cursor-pointer"
      :class="{' !bg-gray-800 !text-white': activeTab == index}"
        x-on:click="activeTab = index"
        x-text="tab"
      >
      </button>
    </template>
  </div>

  {{ $slot }}

</div>
