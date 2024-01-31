<?php

use Carbon\Carbon;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use App\Models\Field;
use App\Models\Package;
use App\Models\Allotment;
use Livewire\Attributes\Url;
use App\Livewire\Forms\FilterCustomerHistoryBookingForm;

new class extends Component {
    public FilterCustomerHistoryBookingForm $filterCustomerHistoryBookingForm;


    #[Url(as: 'q')]
    public $search = '';

    public function mount () {
        $this->filterCustomerHistoryBookingForm->setFilter();
    }

    public function with(): array
    {
        $filters = [
            'search' => $this->search,
            'date_from' => $this->filterCustomerHistoryBookingForm->date_from,
            'date_until' => $this->filterCustomerHistoryBookingForm->date_until,
            'day' =>  $this->filterCustomerHistoryBookingForm->day,
            'field' => $this->filterCustomerHistoryBookingForm->field,
            'status' => 'confirmed'
        ];

        $allotments = Allotment::filter($filters)
            ->where('user_id', auth()->user()->id)
            ->orderBy('allotments.date')
            ->orderBy('allotments.start_time')
            ->get();

        $today = Carbon::parse(todayDate());
        $showedBookingDateUntil = Carbon::parse($this->filterCustomerHistoryBookingForm->date_until);

        $fieldAutoCompletes = Field::filter($this->filterCustomerHistoryBookingForm->field)->select('name')->get();

        return [
            'fieldAutoCompletes' => $fieldAutoCompletes,
            'allotments' => $allotments,
            'allotmentsBookedByCurrentUser' => $allotments->filter(function($value) {
              return $value['user_id'] === auth()->user()->id && $value['status'] === 'hold';
            }),
            'allotmentsByDate' => $allotments->groupBy('date')->all(),
            'totalShowedBookingDays' => Carbon::parse($this->filterCustomerHistoryBookingForm->date_from)->diffInDays(Carbon::parse($showedBookingDateUntil)),
        ];
    }

    public function searchBookings() {
        $this->resetPage();
    }

    public function filterHistoryBookings() {
        $this->filterCustomerHistoryBookingForm->validate();

        $this->dispatch('close-modal', 'filter-customer-history-booking');
    }

    public function resetFilter() {
        $this->filterCustomerHistoryBookingForm->setFilter();
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
      <h1 class="text-xl font-bold mt-2">History Bookings</h1>
    </div>

    <button @click="$dispatch('open-modal', 'filter-customer-history-booking')" class="flex items-center bg-white mb-2 shadow-sm py-2 px-4 me-4 border border-slate-500 text-slate-500  rounded-md hover:bg-indigo-800 hover:text-gray-200">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 me-2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
      </svg>

      <span>Filter</span>
   </button>

    <x-forms.booking.customer.filter-history-booking :fieldAutoCompletes="$fieldAutoCompletes"/>

    {{-- list --}}
    <div id="draggable-zone" class="w-full overflow-x-auto whitespace-nowrap">
      <div id="draggable-content" class="inline-block whitespace-nowrap cursor-grab">
        @for($day = 0; $day <= $totalShowedBookingDays; $day++)
            @php
                $rowCarbonDate = Carbon::parse($filterCustomerHistoryBookingForm->date_from)->addDays($day);
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
                    'self-booked-hold' => [
                      'bg' => 'bg-indigo-600',
                      'icon' => '
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-white">
                          <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z" clip-rule="evenodd" />
                        </svg>',
                      'color' => '!text-white',
                      'statusColor' => '!text-white'
                    ],
                    'other-booked' => [
                      'bg' => 'bg-gray-600',
                      'icon' => '
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-400">
                          <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z" clip-rule="evenodd" />
                        </svg>',
                      'color' => '!text-white',
                      'statusColor' => '!text-white'
                    ],
                  ];
                  
                  if ($allotment->user_id == auth()->user()->id) {
                    switch ($allotment->status) {
                      case 'hold':
                        $cardLookup = $statusClassStyle['self-booked-hold'];
                        break;
                      case 'verifying':
                        $cardLookup = $statusClassStyle['other-booked'];
                        break;
                      default:
                        $cardLookup = $statusClassStyle['other-booked'];
                        break;
                    }
                  } else {
                    $cardLookup = $allotment->status === 'available' ? $statusClassStyle['available'] : $statusClassStyle['other-booked'];
                  }
                @endphp
    
                <div wire:click="handleBooking('{{ $allotment->id }}')" class="bg-indigo-600 rounded-md mb-2 px-4 py-6 min-w-40 transition-all hover:cursor-pointer">
    
                  <div class="text-center {{ $cardLookup['color'] }}">
                    <h5 class="{{ $cardLookup['statusColor'] }} text-lg  mb-2">{{ 
                      ucwords($allotment->field_name)}}</h5>
                    <h5 class="text-xl font-bold mb-2">{{ $formattedStartTime . ' - ' . $formattedEndTime}}</h5>
                    <h3 class=" mb-2">{{ formatToRupiah($allotment->price)}}</h3>
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

    {{-- klu ad allotment yg pnya user --}}
    <div wire:click="redirectToPayment" class=" bg-white p-4 sticky bottom-0 transition-all {{ count($allotmentsBookedByCurrentUser) ? '' : 'hidden' }}">
      <button class="w-full py-4 text-center bg-indigo-600 rounded-md  text-white">Continue To Payment</button>
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
