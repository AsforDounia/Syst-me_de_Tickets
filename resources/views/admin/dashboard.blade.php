

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500">Total Tickets</div>
                    <div class="text-2xl font-bold">{{ $totalTickets }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500">Open Tickets</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $openTickets }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500">Total Users</div>
                    <div class="text-2xl font-bold">{{ $totalUsers }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500">Total Agents</div>
                    <div class="text-2xl font-bold">{{ $totalAgents }}</div>
                </div>
            </div>

            <!-- Gestion des catégories de tickets -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4" x-data="{ showForm: false }">
                    <h3 class="text-lg font-semibold mb-4">Gestion des Catégories</h3>
                    <x-primary-button class="ms-3" @click="showForm = !showForm">
                        {{ __('Ajouter une Catégorie') }}
                    </x-primary-button>
                    <div x-show="showForm"  class="absolute top-0 right-0 w-full  min-h-screen flex justify-center items-center bg-gray-500 opacity-95">
                        <div class="fixed inset-0 flex justify-center items-center">
                            <div class="bg-white w-2/4 max-w-lg p-6 rounded-lg shadow-lg relative ">


                                <button @click="showForm = false" class="absolute top-0 right-0 px-4 text-gray-600 hover:text-gray-900 text-xl">
                                    &times;
                                </button>


                                <h3 class="text-lg font-semibold mb-4 text-center">Ajouter une nouvelle catégorie</h3>
                                <form action="{{ route('admin.addCategory') }}" method="POST" class="space-y-4">
                                    @csrf


                                    <div>
                                        <x-input-label for="name" :value="__('Nom')" />
                                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" placeholder="Nom de la catégorie" :value="old('name')" required autofocus />
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>


                                    <div class="mt-4">
                                        <x-input-label for="description" :value="__('Description')" />
                                        <textarea id="description" name="description" cols="30" rows="3" class="block mt-1 w-full border p-2 rounded" required placeholder="Entrez une description pour la catégorie"></textarea>
                                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="flex items-center justify-center mt-4">
                                        <x-primary-button class="ms-3">
                                            {{ __('Ajouter') }}
                                        </x-primary-button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>


                <table class="min-w-full mt-4 border border-gray-300">
                    <thead>
                        <tr>
                            <th class="text-center  border border-gray-300">Nom</th>
                            <th class="text-center border border-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr class="border-b">
                            <td class="p-2 border-r">{{ $category->name }}</td>
                            <td class="p-2">
                                <button class="text-blue-500">Modifier</button>
                                <button class="text-red-500">Supprimer</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Gestion des tickets -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Liste des Tickets</h3>
                    <div class="flex w-1/2 gap-4">
                        <select class="border px-4 py-2 rounded w-full md:w-auto">
                            <option>Filtrer par Statut</option>
                            <option>En attente</option>
                            <option>En cours</option>
                            <option>Résolu</option>
                        </select>
                        <select class="border px-4 py-2 rounded w-full md:w-auto">
                            <option>Filtrer par Catégorie</option>
                        </select>
                        <select class="border px-4 py-2 rounded w-full md:w-auto">
                            <option>Filtrer par Agent</option>
                        </select>
                    </div>
                </div>

                <table class="min-w-full border border-gray-300">
                    <thead>
                        <tr class="border-b">
                            <th class="text-center border-r p-2">ID</th>
                            <th class="text-center border-r p-2">Titre</th>
                            <th class="text-center border-r p-2">Statut</th>
                            <th class="text-center border-r p-2">Créé Par</th>
                            <th class="text-center border-r p-2">Assigné À</th>
                            <th class="text-center p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr class="border-b">
                                <td class="text-center p-2 border-r">{{ $ticket->id }}</td>
                                <td class="text-center p-2 border-r">{{ $ticket->title }}</td>
                                <td class="text-center p-2 border-r">{{ $ticket->status }}</td>
                                <td class="text-center p-2 border-r">{{ $ticket->user->name }}</td>
                                <td class="text-center p-2 border-r">
                                    {{ $ticket->agent ? $ticket->agent->name : 'Non assigné' }}
                                </td>
                                <td class="text-center p-2">
                                    <button class="text-blue-500">Détails</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
