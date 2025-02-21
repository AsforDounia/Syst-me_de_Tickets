<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Gestion des Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data>
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="w-full text-right mb-4">
                <button @click="$store.ticketModal.open()" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                    Ajouter un ticket
                </button>
            </div>
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priorité</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tickets as $ticket)
                                <tr>
                                    <td class="px-6 py-4">{{ $ticket->title }}</td>
                                    <td class="px-6 py-4">{{ $ticket->category->name }}</td>
                                    <td class="px-6 py-4">{{ ucfirst($ticket->priority) }}</td>
                                    <td class="px-6 py-4">{{ ucfirst($ticket->status) }}</td>
                                    <td class="px-6 py-4 space-x-2">

                                        @if($ticket->status === 'open')
                                            <button type="button"
                                                    @click="$store.ticketModal.edit({{ $ticket->id }}, '{{ $ticket->title }}', '{{ $ticket->description }}', '{{ $ticket->category_id }}', '{{ $ticket->priority }}')"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                Modifier
                                            </button>
                                        @else
                                            <span class="text-gray-400 cursor-not-allowed">Modifier</span>
                                        @endif
                                        {{-- <button type="button"
                                                @click="$store.ticketModal.edit({{ $ticket->id }}, '{{ $ticket->title }}', '{{ $ticket->description }}', '{{ $ticket->category_id }}', '{{ $ticket->priority }}')"
                                                class="text-indigo-600 hover:text-indigo-900">
                                            Modifier
                                        </button> --}}
                                        @if($ticket->status === 'resolved')
                                        <button type="button"
                                                @click="$store.closeModal.open({{ $ticket->id }})"
                                                class="text-red-600 hover:text-red-900">
                                            Close
                                        </button>
                                    @else
                                        <span class="text-gray-400 cursor-not-allowed">Close</span>
                                    @endif
                                        {{-- <button type="button"
                                                @click="$store.closeModal.open({{ $ticket->id }})"
                                                class="text-red-600 hover:text-red-900">
                                            Close
                                        </button> --}}

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <!-- Alpine.js Stores -->
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('ticketModal', {
                    isOpen: false,
                    isEdit: false,
                    ticketId: null,
                    open() {
                        this.isOpen = true;
                        this.isEdit = false;
                        this.ticketId = null;
                    },
                    edit(id, title, description, categoryId, priority) {
                        this.ticketId = id;
                        this.isEdit = true;
                        this.isOpen = true;
                        setTimeout(() => {
                            document.getElementById('ticketTitle').value = title;
                            document.getElementById('ticketDescription').value = description;
                            document.getElementById('ticketCategory').value = categoryId;
                            document.getElementById('ticketPriority').value = priority;
                        }, 100);
                    },
                    close() {
                        this.isOpen = false;
                    },
                });

                Alpine.store('closeModal', {
                    isOpen: false,
                    ticketId: null,
                    open(id) {
                        this.ticketId = id;
                        this.isOpen = true;
                    },
                    close() {
                        this.isOpen = false;
                        this.ticketId = null;
                    }
                });
            });
        </script>

        <!-- Modal Ajouter/Modifier Ticket -->
        <div x-show="$store.ticketModal.isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div @click.away="$store.ticketModal.close()" class="bg-white rounded-lg p-8 max-w-md w-full">
                    <h3 x-text="$store.ticketModal.isEdit ? 'Modifier le ticket' : 'Ajouter un ticket'" class="text-lg font-medium mb-4"></h3>
                    <form x-bind:action="$store.ticketModal.isEdit ? '{{ route('tickets.update') }}' : '{{ route('tickets.store') }}'" method="POST">
                        @csrf
                        <template x-if="$store.ticketModal.isEdit">
                            @method('PUT')
                        </template>
                        <template x-if="$store.ticketModal.isEdit">
                            <input type="hidden" name="id" x-bind:value="$store.ticketModal.ticketId">
                        </template>
                        <div class="space-y-4">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                                <input type="text" name="title" id="ticketTitle" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="ticketDescription" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"></textarea>
                            </div>
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie</label>
                                <select name="category_id" id="ticketCategory" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700">Priorité</label>
                                <select name="priority" id="ticketPriority" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                    <option value="low">Basse</option>
                                    <option value="medium">Moyenne</option>
                                    <option value="high">Élevée</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-2">
                            <button type="button" @click="$store.ticketModal.close()"
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

        <!-- Modal fermeture -->
        <div x-show="$store.closeModal.isOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div @click.away="$store.closeModal.close()" class="bg-white rounded-lg p-8 max-w-md w-full">
                    <h3 class="text-lg font-medium mb-4">Confirmer la fermeture</h3>
                    <p class="mb-4 text-gray-600">Êtes-vous sûr de vouloir fermer ce ticket ?</p>

                    <!-- Form with hidden ticket ID -->
                    <form action="{{ route('client.closeTicket') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Hidden input to send ticket ID -->
                        <input type="hidden" name="ticket_id" x-model="$store.closeModal.ticketId">

                        <div class="flex justify-end space-x-2">
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md">
                                Fermer
                            </button>
                            <button type="button" @click="$store.closeModal.close()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100">
                                Annuler
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

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erreur!',
                text: "{{ session('error') }}",
                timer: 4000,
                showConfirmButton: true
            }).then(() => {
                @php session()->forget('error'); @endphp
            });
        @endif
    });
</script>

