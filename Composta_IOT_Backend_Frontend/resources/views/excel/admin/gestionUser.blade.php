@extends('admin.dashboard')

@section('content')
{{-- Usamos un padding general y un fondo suave para toda la página --}}
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-3">
        {{-- Título más prominente --}}
        <h1 class="text-3xl font-semibold text-gray-900 flex items-center">
            <i class="fas fa-users mr-3 text-green-600"></i>
            Gestión de Usuarios
        </h1>
        
        {{-- Botón con una sombra más definida y una transición suave --}}
        <a href="{{ route('admins.create') }}" 
           class="inline-flex items-center px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition-all duration-200 ease-in-out">
            <i class="fas fa-plus-circle mr-2"></i>
            Nuevo Usuario
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-300 text-green-800 font-medium shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-2 text-green-600"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- La clase 'overflow-hidden' es clave para que los bordes redondeados funcionen con la tabla interior --}}
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white flex justify-between items-center p-5">
            <h6 class="m-0 font-semibold flex items-center text-lg">
                <i class="fas fa-list mr-2"></i>
                Listado de Usuarios
            </h6>
            {{-- Badge semitransparente, mucho más elegante sobre el gradiente --}}
            <span class="bg-white/25 text-white px-3 py-1 rounded-full text-sm font-semibold">
                Total: {{ count($user1) }}
            </span>
        </div>

        <div class="card-body p-0">
            {{-- Contenedor para que la tabla sea responsive horizontalmente --}}
            <div class="overflow-x-auto">
                <table class="w-full align-middle text-sm">
                    {{-- Encabezado de tabla más limpio --}}
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-center">#</th>
                            <th class="px-6 py-3 text-left">Nombre Completo</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-center">Rol</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    {{-- Cuerpo de la tabla con divisiones finas --}}
                    <tbody class="divide-y divide-gray-200">
                        @forelse($user1 as $user)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 text-center font-medium text-gray-600">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-left">
                                    {{-- Texto principal más oscuro, para jerarquía visual --}}
                                    <div class="text-sm font-medium text-gray-900 capitalize">
                                        {{ $user->name }} {{ $user->firstLastName }} {{ $user->secondLastName }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-left text-gray-700">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- Badges más sutiles y de menor tamaño --}}
                                    @switch($user->role)
                                        @case('admin')
                                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold inline-flex items-center">
                                                <i class="fas fa-user-shield mr-1.5"></i> Admin
                                            </span>
                                            @break
                                        @case('user')
                                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-800 text-xs font-semibold inline-flex items-center">
                                                <i class="fas fa-user mr-1.5"></i> Usuario
                                            </span>
                                            @break
                                        @case('client')
                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold inline-flex items-center">
                                                <i class="fas fa-user-friends mr-1.5"></i> Cliente
                                            </span>
                                            @break
                                        @default
                                            <span class="px-3 py-1 rounded-full bg-gray-400 text-white text-xs font-semibold inline-flex items-center">
                                                <i class="fas fa-question-circle mr-1.5"></i> Desconocido
                                            </span>
                                    @endswitch
                                </td>
                                {{-- Botones de acción más pequeños y refinados --}}
                                <td class="px-6 py-4 flex justify-center gap-2">
                                    <a href="{{ route('admins.edit', $user) }}" 
                                       class="inline-flex items-center px-2.5 py-1 rounded-md bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-medium shadow-sm transition">
                                        <i class="fas fa-edit mr-1"></i> Editar
                                    </a>

                                    <form action="{{ route('admins.destroy', $user) }}" method="POST" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-2.5 py-1 rounded-md bg-red-600 hover:bg-red-700 text-white text-xs font-medium shadow-sm transition">
                                            <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-gray-500 text-center">
                                    <i class="fas fa-info-circle mr-1"></i> No hay usuarios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Si estás usando paginación, ponla aquí. 
                 Tendrá un borde superior para separarla de la tabla. --}}
            {{-- <div class="p-5 border-t border-gray-200">
                {{ $user1->links() }}
            </div> --}}

        </div>
    </div>
</div>
@endsection