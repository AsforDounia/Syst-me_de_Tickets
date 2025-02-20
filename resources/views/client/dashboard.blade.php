<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Client Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500">Total Tickets</div>
                        <div class="text-2xl font-bold">5</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500">Open Tickets</div>
                        <div class="text-2xl font-bold text-yellow-600">1</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500">Resolved Tickets</div>
                        <div class="text-2xl font-bold text-green-600">3</div>
                    </div>
                </div>
            </div>

            <!-- Recent Tickets -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">My Recent Tickets</h3>
                        <a href="xxxx" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Create New Ticket
                        </a>
                    </div>
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="text-left">ID</th>
                                <th class="text-left">Title</th>
                                <th class="text-left">Status</th>
                                <th class="text-left">Category</th>
                                <th class="text-left">Assigned To</th>
                                <th class="text-left">Created At</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
