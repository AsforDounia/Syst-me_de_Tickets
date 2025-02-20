<div class="w-64 bg-gray-800 text-white min-h-screen h-auto  ">
    <ul class="space-y-4 mt-6">


        @if(auth()->user()->role == 'admin')
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-lg hover:bg-gray-700 rounded">
                    <i class="fas fa-tachometer-alt mr-4"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.tickets') }}" class="flex items-center px-6 py-3 text-lg hover:bg-gray-700 rounded">
                    <i class="fas fa-ticket-alt mr-4"></i> Tickets
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories') }}" class="flex items-center px-6 py-3 text-lg hover:bg-gray-700 rounded">
                    <i class="fas fa-cogs mr-4"></i> Catégories
                </a>
            </li>
            <li>
                <a href="" class="flex items-center px-6 py-3 text-lg hover:bg-gray-700 rounded">
                    <i class="fas fa-users mr-4"></i> Utilisateurs
                </a>
            </li>
            <li>
                <a href="" class="flex items-center px-6 py-3 text-lg hover:bg-gray-700 rounded">
                    <i class="fas fa-user-tie mr-4"></i> Agents
                </a>
            </li>
            <li>
                <a href="" class="flex items-center px-6 py-3 text-lg hover:bg-gray-700 rounded">
                    <i class="fas fa-cogs mr-4"></i> Paramètres
                </a>
            </li>
        @elseif(auth()->user()->role == 'agent') <!-- Agent Role -->
        <li>
            <a href="" class="flex items-center px-6 py-3 text-lg hover:bg-gray-700 rounded">
                <i class="fas fa-tachometer-alt mr-4"></i> Dashboard
            </a>
        </li>
            <li>
                <a href="" class="flex items-center px-6 py-3 text-lg hover:bg-gray-700 rounded">
                    <i class="fas fa-ticket-alt mr-4"></i> Mes Tickets
                </a>
            </li>
            <li>
                <a href="" class="flex items-center px-6 py-3 text-lg hover:bg-gray-700 rounded">
                    <i class="fas fa-user mr-4"></i> Mon Profil
                </a>
            </li>
        @elseif(auth()->user()->role == 'client') <!-- Regular User Role -->
        <li>
            <a href="" class="flex items-center px-6 py-3 text-lg hover:bg-gray-700 rounded">
                <i class="fas fa-tachometer-alt mr-4"></i> Dashboard
            </a>
        </li>
            <li>
                <a href="" class="flex items-center px-6 py-3 text-lg hover:bg-gray-700 rounded">
                    <i class="fas fa-ticket-alt mr-4"></i> Mes Tickets
                </a>
            </li>
            <li>
                <a href="" class="flex items-center px-6 py-3 text-lg hover:bg-gray-700 rounded">
                    <i class="fas fa-user mr-4"></i> Mon Profil
                </a>
            </li>
        @endif
    </ul>
</div>
