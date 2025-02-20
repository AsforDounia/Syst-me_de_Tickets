<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Gestion des Catégories') }}
        </h2>
    </x-slot>


    <div class="py-12" x-data>
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="w-full text-right mb-4">
                <button @click="$store.categoryModal.open()" class=" px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                    Ajouter une catégorie
                </button>
            </div>
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($categories as $category)
                                <tr>
                                    <td class="px-6 py-4">{{ $category->name }}</td>
                                    <td class="px-6 py-4">{{ $category->description }}</td>
                                    <td class="px-6 py-4 space-x-2">
                                        <button type="button"
                                                @click="$store.categoryModal.edit({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}')"
                                                class="text-indigo-600 hover:text-indigo-900">
                                            Modifier
                                        </button>
                                        <button type="button"
                                                @click="$store.deleteModal.open({{ $category->id }})"
                                                class="text-red-600 hover:text-red-900">
                                            Supprimer
                                        </button>
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
                Alpine.store('categoryModal', {
                    isOpen: false,
                    isEdit: false,
                    categoryId: null,
                    open() {
                        this.isOpen = true;
                        this.isEdit = false;
                        this.categoryId = null;
                    },
                    edit(id, name, description) {
                        this.categoryId = id;
                        this.isEdit = true;
                        this.isOpen = true;
                        // Set form values
                        setTimeout(() => {
                            document.getElementById('categoryName').value = name;
                            document.getElementById('categoryDescription').value = description;
                        }, 100);
                    },
                    close() {
                        this.isOpen = false;
                    }
                });

                Alpine.store('deleteModal', {
                    isOpen: false,
                    categoryId: null,
                    open(id) {
                        this.categoryId = id;
                        this.isOpen = true;
                    },
                    close() {
                        this.isOpen = false;
                    }
                });
            });
        </script>

        <!-- Modal Ajouter/Modifier Catégorie -->
        <div x-show="$store.categoryModal.isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div @click.away="$store.categoryModal.close()" class="bg-white rounded-lg p-8 max-w-md w-full">
                    <h3 x-text="$store.categoryModal.isEdit ? 'Modifier la catégorie' : 'Ajouter une catégorie'" class="text-lg font-medium mb-4"></h3>
                    <form action="/admin/addCategory" method="POST">
                        @csrf
                        <template x-if="$store.categoryModal.isEdit">
                            @method('PUT')
                        </template>
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                                <input type="text" name="name" id="categoryName" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="categoryDescription" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"></textarea>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-2">
                            <button type="button" @click="$store.categoryModal.close()"
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

        <!-- Modal Suppression -->
        <div x-show="$store.deleteModal.isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div @click.away="$store.deleteModal.close()" class="bg-white rounded-lg p-8 max-w-md w-full">
                    <h3 class="text-lg font-medium mb-4">Confirmer la suppression</h3>
                    <p class="mb-4 text-gray-600">Êtes-vous sûr de vouloir supprimer cette catégorie ?</p>
                    <form @submit.prevent="submitDeleteForm" :action="`/admin/categories/${$store.deleteModal.categoryId}`" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-end space-x-2">
                            <button type="button" @click="$store.deleteModal.close()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md">
                                Supprimer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
