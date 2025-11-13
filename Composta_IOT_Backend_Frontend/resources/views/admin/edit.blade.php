@extends('admin.dashboard')

@section('content')
    <div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

        {{-- Alertas Refinadas (sin JS) --}}
        @if (session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-300 text-green-800 font-medium shadow-sm flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600"></i>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-300 text-red-800 font-medium shadow-sm flex items-center">
                <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white flex justify-between items-center p-5">
                <h5 class="m-0 font-semibold text-lg flex items-center">
                    <i class="fas fa-user-edit mr-2"></i>
                    Editar Usuario
                </h5>
                
                <a href="{{ route('gU') }}" 
                   title="Volver"
                   class="inline-flex items-center justify-center h-11 w-11 rounded-full text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                    <i class="fas fa-long-arrow-alt-left text-2xl"></i>
                </a>
            </div>

            <div class="bg-white p-6">
                
                {{-- Validación de errores (Estilo Tailwind) --}}
                @if ($errors->any())
                    <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-300 text-red-900 shadow-sm">
                        <h6 class="font-semibold mb-2 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Errores encontrados:
                        </h6>
                        <ul class="mb-0 list-disc list-inside text-sm text-red-800">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admins.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $user->id ?? '' }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="space-y-5">
                            <div>
                                <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       id="edit_name" 
                                       name="name" 
                                       value="{{ old('name', $user->name ?? '') }}" 
                                       {{-- CAMBIO: Añadido 'py-2.5' --}}
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm @error('name') border-red-500 @enderror py-2.5"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="edit_firstLastName" class="block text-sm font-medium text-gray-700 mb-1">Primer Apellido <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       id="edit_firstLastName" 
                                       name="firstLastName" 
                                       value="{{ old('firstLastName', $user->firstLastName ?? '') }}" 
                                       {{-- CAMBIO: Añadido 'py-2.5' --}}
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm @error('firstLastName') border-red-500 @enderror py-2.5"
                                       required>
                                @error('firstLastName')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="edit_secondLastName" class="block text-sm font-medium text-gray-700 mb-1">Segundo Apellido</label>
                                <input type="text" 
                                       id="edit_secondLastName" 
                                       name="secondLastName" 
                                       value="{{ old('secondLastName', $user->secondLastName ?? '') }}" 
                                       {{-- CAMBIO: Añadido 'py-2.5' --}}
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm @error('secondLastName') border-red-500 @enderror py-2.5">
                                @error('secondLastName')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label for="edit_username" class="block text-sm font-medium text-gray-700 mb-1">Nombre de Usuario</label>
                                <input type="text" 
                                       id="edit_username" 
                                       name="username" 
                                       value="{{ old('username', $user->username ?? '') }}" 
                                       {{-- CAMBIO: Añadido 'py-2.5' --}}
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm @error('username') border-red-500 @enderror py-2.5">
                                @error('username')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico <span class="text-red-500">*</span></label>
                                <input type="email" 
                                       id="edit_email" 
                                       name="email"
                                       value="{{ old('email', $user->email ?? '') }}" 
                                       {{-- CAMBIO: Añadido 'py-2.5' --}}
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm @error('email') border-red-500 @enderror py-2.5"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-1">Rol <span class="text-red-500">*</span></label>
                                <select id="edit_role" 
                                        name="role" 
                                        {{-- CAMBIO: Añadido 'py-2.5' --}}
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm @error('role') border-red-500 @enderror py-2.5"
                                        required>
                                    <option value="" disabled>Seleccione un rol</option>
                                    <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                    <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>Usuario</option>
                                    <option value="client" {{ old('role', $user->role ?? '') == 'client' ? 'selected' : '' }}>Cliente</option>
                                </select>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition-all duration-200 ease-in-out">
                            <i class="fas fa-save mr-2"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection