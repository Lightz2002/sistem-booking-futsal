<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

new class extends Component {

    use WithPagination;

    #[Url(as: 'q')]
    public $search = '';
    #[Url(as: 'sort')]
    public $sortBy = 'date';
    #[Url(as: 'direction')]
    public $sortDirection = 'asc';

    public function with(): array
    {
        $tableColumns = [
            [
                'key' => 'name',
                'label' => 'Name',
            ],
            [
                'key' => 'email',
                'label' => 'Email',
            ],
            [
                'key' => 'phone_no',
                'label' => 'Phone No',
            ],
            [
                'key' => 'address',
                'label' => 'Address',
                'component' => 'columns.customer.address'
            ],
            [
                'key' => 'action',
                'label' => 'Action',
                'component' => 'columns.customer.action'
            ]
        ];

        return [
            'details' => User::filter($this->search)->where('role', 'customer')->paginate(20),
            'detailColumns' => $tableColumns,
        ];
    }


    public function searchCustomers() {
        $this->resetPage();
    }

    public function viewCustomerDetail($customerId) {
        $this->redirectRoute('customers.detail', ['customer' => $customerId]);
    }

}; ?>

<div class="h-full  rounded-md mx-auto">
    <x-alert name="alert"></x-alert>

    <x-search model="search" search="searchCustomers" />

    <div class="flex items-center mb-4">
      <h1 class="font-bold text-2xl">Customers</h1>
    </div>

    <x-table :details="$details" :detailColumns="$detailColumns" :sortBy="$sortBy" :sortDirection="$sortDirection"></x-table>

</div>
