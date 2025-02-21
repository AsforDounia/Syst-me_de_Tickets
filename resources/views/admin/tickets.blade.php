<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Gestion des Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        assignModal: false,
        statusModal: false,
        currentTicketId: null,
        openAssign(ticketId) {
            this.currentTicketId = ticketId;
            this.assignModal = true;
        },
        openStatus(ticketId) {
            this.currentTicketId = ticketId;
            this.statusModal = true;
        },
        categoryModal: false,
            currentTicketId: null,
            openCategoryModal(ticketId) {
                this.currentTicketId = ticketId;
                this.categoryModal = true;
        }
    }">

            <!-- Ticket Statistics Section -->
        <div class="grid grid-cols-1 md:grid-cols-5 mx-8 gap-4 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500">Total Tickets</div>
                <div class="text-2xl font-bold">{{ $totalTickets }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500">Open Tickets</div>
                <div class="text-2xl font-bold text-yellow-600">{{ $openTickets }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500">In Progress</div>
                <div class="text-2xl font-bold text-blue-600">{{ $inProgressTickets }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500">Resolved</div>
                <div class="text-2xl font-bold text-green-600">{{ $resolvedTickets }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500">Closed</div>
                <div class="text-2xl font-bold text-red-600">{{ $closedTickets }}</div>
            </div>
        </div>

        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Liste des tickets -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created by</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sujet</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tickets as $ticket)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->id }}</td>
                                    <td class="px-6 py-4">{{ $ticket->user?->name ?? 'Non assigné' }}</td>
                                    <td class="px-6 py-4">{{ $ticket->description }}</td>
                                    <td class="px-6 py-4">
                                        {{-- {{ $ticket->category ? $ticket->category->name : 'null' }} --}}
                                        @if($ticket->category)
                                        <button @click="openCategoryModal({{ $ticket->id }})" type="button" class="text-blue-600 hover:text-gray-900">
                                            {{ $ticket->category->name }}
                                        </button>

                                        @else
                                            <button @click="openCategoryModal({{ $ticket->id }})" type="button" class="text-blue-600 hover:text-gray-900">
                                                Choisir Catégorie
                                            </button>
                                        @endif

                                    <td class="px-6 py-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($ticket->agent) bg-blue-100 text-blue-800 @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(!$ticket->agent)
                                            <button @click="openAssign({{ $ticket->id }})" type="button" class="text-indigo-600 hover:text-indigo-900">
                                                Assigner
                                            </button>
                                        @else
                                            <button @click="openAssign({{ $ticket->id }})" type="button" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $ticket->agent?->name }}
                                            </button>

                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        {{-- @if(!$ticket->agent)
                                            <button @click="openAssign({{ $ticket->id }})" type="button" class="text-indigo-600 hover:text-indigo-900">
                                                Assigner
                                            </button>
                                        @endif --}}
                                        @if(!$ticket->agent)
                                        <span class="text-gray-400 cursor-not-allowed">
                                                Ticket is not assigned
                                        </span>
                                        @else
                                        <button @click="openStatus({{ $ticket->id }})" type="button" class="text-blue-600 hover:text-gray-900">
                                            Changer statut
                                        </button>
                                        @endif


                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $tickets->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Assigner un Agent -->
        <div x-show="assignModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div @click.away="assignModal = false" class="bg-white rounded-lg p-8 max-w-md w-full">
                    <h3 class="text-lg font-medium mb-4">Assigner un agent</h3>
                    <form method="POST" :action="`/admin/tickets/${currentTicketId}/assign`">
                        @csrf
                        @method('PUT')
                        <select name="agent_id" class="w-full mb-4 rounded-md border-gray-300 p-2">
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="assignModal = false"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md">
                                Confirmer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Changer Statut -->
        <div x-show="statusModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div @click.away="statusModal = false" class="bg-white rounded-lg p-8 max-w-md w-full">
                    <h3 class="text-lg font-medium mb-4">Changer le statut</h3>
                    <form method="POST" :action="`/admin/tickets/${currentTicketId}/status`">
                        @csrf
                        @method('PUT')
                        <select name="status" class="w-full mb-4 rounded-md border-gray-300 p-2">
                            <option value="open">En attente</option>
                            <option value="in_progress">En cours</option>
                            <option value="resolved">Résolu</option>
                            <option value="closed">Close</option>

                        </select>
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="statusModal = false"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md">
                                Confirmer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>





    </div>
</x-app-layout>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: true
            }).then(() => {
                @php session()->forget('success'); @endphp
            });
        @endif
    });
</script>
