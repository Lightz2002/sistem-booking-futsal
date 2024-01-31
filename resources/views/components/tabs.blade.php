@props([
    'tabs' => ['Main'],
])

<div x-data="{
  tabs: @js($tabs),
  activeTab: 0 // Index of the initially active tab
}">

  <div class="flex mb-2 rounded-md  w-full">
    <template x-for="tab, index in tabs" :key="index">
      <button
      class="text-sm  rounded-md text-gray-600 font-bold px-8 py-2 hover:bg-gray-200 hover:cursor-pointer"
      :class="{'  !bg-white !text-gray-800': activeTab == index}"
        x-on:click="activeTab = index"
        x-text="tab"
      >
      </button>

    </template>

    @isset($dropdown) {{ $dropdown }} @endisset
  </div>

  {{ $content }}

</div>
