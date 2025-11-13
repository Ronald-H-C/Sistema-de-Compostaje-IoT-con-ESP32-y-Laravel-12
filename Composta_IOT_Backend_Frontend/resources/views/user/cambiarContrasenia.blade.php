@extends('user.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-3">
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-6">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Cambiar Contraseña</h2>

        @if(session('success'))
            <div class="mb-4 px-4 py-2 text-green-800 bg-green-100 rounded-md text-center">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="mb-4 px-4 py-2 text-red-800 bg-red-100 rounded-md text-center">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('contrasenia') }}" class="space-y-4">
            @csrf

            <!-- Contraseña actual -->
            <div>
                <label for="currentPassword" class="block text-sm font-medium text-gray-700">Contraseña Actual</label>
                <div class="relative">
                    <input type="password" name="Acontra" id="currentPassword" required
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10">
                    <button type="button" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                            onmousedown="togglePassword('currentPassword', true)" 
                            onmouseup="togglePassword('currentPassword', false)" 
                            onmouseleave="togglePassword('currentPassword', false)"
                            ontouchstart="togglePassword('currentPassword', true)" 
                            ontouchend="togglePassword('currentPassword', false)">
                        <!-- Icono ojo -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Nueva contraseña -->
            <div>
                <label for="newPassword" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                <div class="relative">
                    <input type="password" name="Ncontra" id="newPassword" required
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10">
                    <button type="button" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                            onmousedown="togglePassword('newPassword', true)" 
                            onmouseup="togglePassword('newPassword', false)" 
                            onmouseleave="togglePassword('newPassword', false)"
                            ontouchstart="togglePassword('newPassword', true)" 
                            ontouchend="togglePassword('newPassword', false)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Confirmar nueva contraseña -->
            <div>
                <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
                <div class="relative">
                    <input type="password" name="Ccontra" id="confirmPassword" required
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10">
                    <button type="button" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                            onmousedown="togglePassword('confirmPassword', true)" 
                            onmouseup="togglePassword('confirmPassword', false)" 
                            onmouseleave="togglePassword('confirmPassword', false)"
                            ontouchstart="togglePassword('confirmPassword', true)" 
                            ontouchend="togglePassword('confirmPassword', false)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                Actualizar Contraseña
            </button>
        </form>
    </div>
</div>
</div>

<script>
    function togglePassword(id, show) {
        const input = document.getElementById(id);
        input.type = show ? 'text' : 'password';
    }
</script>
@endsection
