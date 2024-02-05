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
                $menus = [
                   
                    [
                        'name' => 'bookings',
                        'route' => 'customer-bookings'
                    ],
                    [
                        'name' => 'upcoming',
                        'route' => 'customer-upcoming-bookings'
                    ],
                    [
                        'name' => 'history',
                        'route' => 'customer-history-bookings'
                    ]
                ];
                break;
            case 'admin':
                $menus = [
                    [
                        'name' => 'customers',
                        'route' => 'customers'
                    ],    
                    [
                        'name' => 'fields',
                        'route' => 'fields'
                    ],
                    [
                        'name' => 'packages',
                        'route' => 'packages'
                    ],
                    [
                        'name' => 'bookings',
                        'route' => 'admin-bookings'
                    ]
                ];
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
            <x-nav-link class="w-full p-4 pl-8" :href="route($menu['route'])" :active="request()->routeIs($menu['route'].'*')" wire:navigate>
                {{ __(ucwords($menu['name'])) }}
            </x-nav-link>
        @endforeach
        </ul>
    </div>
</nav>
