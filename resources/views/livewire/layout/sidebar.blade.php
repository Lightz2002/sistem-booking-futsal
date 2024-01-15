<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public array $menus = [];

     /**
     * Mount the component.
     */
     public function mount(): void
    {
        switch(auth()->user()->role) {
            case 'customer':
                // $menus = ['booking', 'history'];
                $menus = ['dashboard'];
                break;
            case 'admin':
                // $menus = ['field', 'package', 'booking'];
                $menus = ['dashboard', 'fields'];
                break;
        }

        $this->menus = $menus;
        $this->name = Auth::user()->name;
    }

}; ?>


<nav class="hidden bg-white fixed top-0 w-36  sm:block bottom-0 m-0">
    <!-- Navigation Links -->
    <div class="flex flex-col ">
        <div class="py-4 mx-auto">
            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
        </div>

        <ul class="mt-10">
        @foreach($menus as $menu)
            <x-nav-link class="w-full p-4 pl-8" :href="route($menu)" :active="request()->routeIs($menu)" wire:navigate>
                {{ __(ucwords($menu)) }}
            </x-nav-link>
        @endforeach
        </ul>
    </div>
</nav>
