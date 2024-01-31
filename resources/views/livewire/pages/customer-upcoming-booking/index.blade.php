<?php

use Carbon\Carbon;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use App\Models\Field;
use App\Models\Package;
use App\Models\Allotment;
use Livewire\Attributes\Url;
use App\Livewire\Forms\FilterCustomerUpcomingBookingForm;

new class extends Component {
    public FilterCustomerUpcomingBookingForm $filterCustomerUpcomingBookingForm;


    #[Url(as: 'q')]
    public $search = '';

    public function mount () {
        $this->filterCustomerUpcomingBookingForm->setFilter();
    }

    public function with(): array
    {
        $filters = [
            'search' => $this->search,
            'date_from' => $this->filterCustomerUpcomingBookingForm->date_from,
            'date_until' => $this->filterCustomerUpcomingBookingForm->date_until,
            'day' =>  $this->filterCustomerUpcomingBookingForm->day,
            'field' => $this->filterCustomerUpcomingBookingForm->field,
            'status' => ''
        ];

        $allotments = Allotment::filter($filters)
            ->where('user_id', auth()->user()->id)
            ->whereIn('allotments.status', ['verifying', 'confirmed'])
            ->orderBy('allotments.date')
            ->orderBy('allotments.start_time')
            ->get();

        $today = Carbon::parse(todayDate());
        $showedBookingDateUntil = Carbon::parse($this->filterCustomerUpcomingBookingForm->date_until);

        $fieldAutoCompletes = Field::filter($this->filterCustomerUpcomingBookingForm->field)->select('name')->get();

        return [
            'fieldAutoCompletes' => $fieldAutoCompletes,
            'allotments' => $allotments,
            'allotmentsBookedByCurrentUser' => $allotments->filter(function($value) {
              return $value['user_id'] === auth()->user()->id && $value['status'] === 'hold';
            }),
            'allotmentsByDate' => $allotments->groupBy('date')->all(),
            'totalShowedBookingDays' => Carbon::parse($this->filterCustomerUpcomingBookingForm->date_from)->diffInDays(Carbon::parse($showedBookingDateUntil)),
        ];
    }

    public function searchBookings() {
        $this->resetPage();
    }

    public function filterUpcomingBookings() {
        $this->filterCustomerUpcomingBookingForm->validate();

        $this->dispatch('close-modal', 'filter-customer-upcoming-booking');
    }

    public function resetFilter() {
        $this->filterCustomerUpcomingBookingForm->setFilter();
    }

    // public function redirectToPayment() {
    //   $this->redirectRoute('customer-payments');
    // }

}
//

?>

<div class="h-full  rounded-md mx-auto">
    <x-alert name="alert"></x-alert>

    <div class="mb-6">
      {{-- <img class="object-cover w-full h-56 rounded-md" src="{{ asset($field->image) }}" alt="{{ $field->name }}"> --}}
      <h1 class="text-xl font-bold mt-2">Upcoming Bookings</h1>
    </div>

    <button @click="$dispatch('open-modal', 'filter-customer-upcoming-booking')" class="flex items-center bg-white mb-2 shadow-sm py-2 px-4 me-4 border border-slate-500 text-slate-500  rounded-md hover:bg-indigo-800 hover:text-gray-200">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 me-2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
      </svg>

      <span>Filter</span>
   </button>

    <x-forms.booking.customer.filter-upcoming-booking :fieldAutoCompletes="$fieldAutoCompletes"/>

    {{-- list --}}
    <div id="draggable-zone" class="w-full overflow-x-auto whitespace-nowrap">
      <div id="draggable-content" class="inline-block whitespace-nowrap cursor-grab">
        @for($day = 0; $day <= $totalShowedBookingDays; $day++)
            @php
                $rowCarbonDate = Carbon::parse($filterCustomerUpcomingBookingForm->date_from)->addDays($day);
                $rowDate = $rowCarbonDate->format('d M');
                $rowDay = $rowCarbonDate->format('l');
                $allotmentsPerDate = $allotmentsByDate[$rowCarbonDate->format('Y-m-d')] ?? [];
            @endphp
    
            <div class="flex gap-4 select-none">
                <div class="flex flex-col justify-center items-center text-center bg-white rounded-md p-4 mb-2 min-w-36">
                    <h3 class="font-bold text-lg mb-2">{{ $rowDate }}</h3>
                    <h5 class="text-md text-indigo-600">{{ $rowDay }}</h5>
                </div>
    
                {{-- loop yg group by itu --}}
                @foreach ($allotmentsPerDate as $allotment)
                @php
                  $formattedStartTime = Carbon::createFromTimeString($allotment->start_time)->format('H:i');
                  $formattedEndTime = Carbon::createFromTimeString($allotment->end_time)->format('H:i');
    
                  $statusClassStyle = [
                    'available' => [
                      'bg' => 'bg-white',
                      'icon' => '
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-indigo-600">
                          <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
                        </svg>',
                      'color' => 'text-black',
                      'statusColor' => 'text-indigo-800'
                    ],
                    'confirmed' => [
                      'bg' => 'bg-green-200',
                      'icon' => '
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-black">
                          <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z" clip-rule="evenodd" />
                        </svg>',
                      'color' => '!text-black',
                      'statusColor' => '!text-black'
                    ],
                    'verifying' => [
                      'bg' => 'bg-indigo-200',
                      'icon' => '
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-400">
                          <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z" clip-rule="evenodd" />
                        </svg>',
                      'color' => '!text-black',
                      'statusColor' => '!text-black'
                    ],
                  ];

                  $cardLookup =  $statusClassStyle[strtolower($allotment->status)];
                @endphp
    
                <div  class="{{ $cardLookup['bg'] }} rounded-md mb-2 px-4 py-6 min-w-40 transition-all hover:cursor-pointer">
    
                  <div class="text-center {{ $cardLookup['color'] }}">
                    <h5 class="{{ $cardLookup['statusColor'] }} text-lg  mb-2">{{ 
                      ucwords($allotment->field_name)}}</h5>
                    <h5 class="text-xl font-bold mb-2">{{ $formattedStartTime . ' - ' . $formattedEndTime}}</h5>
                    <h3 class=" mb-2">{{ formatToRupiah($allotment->price)}}</h3>
                    <h3 class="text-sm mb-2">{{ ucwords($allotment->status) }}</h3>
                  </div>
                </div>
                @endforeach
    
                @if (!$allotmentsPerDate)
                  <div class="w-full p-4 bg-white flex items-center mb-2">
                    <h3 class="text-gray-400">No Booking Available !</h3>
                  </div>
                @endif
            </div>
        @endfor
      </div>
    </div>

      @once
      <script>
       $(document).ready(function() {
          $("#draggable-content").draggable({
            axis: "x", // Allow dragging only horizontally
            drag: function(event, ui) {
              // Update the scrollLeft of the draggable-zone based on the drag movement
              $("#draggable-zone").scrollLeft($("#draggable-zone").scrollLeft() - ui.position.left + ui.originalPosition.left);
              ui.position.left = ui.originalPosition.left; // Prevent vertical movement
            }
          });
        });
      </script>
      @endonce
</div>
