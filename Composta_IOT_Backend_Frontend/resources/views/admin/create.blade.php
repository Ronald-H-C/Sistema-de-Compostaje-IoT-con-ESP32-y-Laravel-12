@extends('admin.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    {{-- Alertas --}}
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

    @if ($errors->any())
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-300 text-red-900 shadow-sm">
            <h6 class="font-semibold mb-2 flex items-center">
                <i class="fas fa-times-circle mr-2"></i>
                Error en la creación del usuario
            </h6>
            <ul class="mb-0 list-disc list-inside text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white flex justify-between items-center p-5">
            <h5 class="m-0 font-semibold text-lg flex items-center">
                <i class="fas fa-user-plus mr-2"></i>
                Nuevo Usuario
            </h5>
            
            <a href="{{ route('gU') }}" 
               title="Volver"
               class="inline-flex items-center justify-center h-11 w-11 rounded-full text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                <i class="fas fa-long-arrow-alt-left text-2xl"></i>
            </a>
        </div>

        <div class="bg-white p-6">
            <form action="{{ route('admins.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre(s) *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" required>
                        </div>

                        <div>
                            <label for="firstLastName" class="block text-sm font-medium text-gray-700 mb-1">Primer Apellido *</label>
                            <input type="text" id="firstLastName" name="firstLastName" value="{{ old('firstLastName') }}" 
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" required>
                        </div>

                        <div>
                            <label for="secondLastName" class="block text-sm font-medium text-gray-700 mb-1">Segundo Apellido</label>
                            <input type="text" id="secondLastName" name="secondLastName" value="{{ old('secondLastName') }}" 
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5">
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nombre de Usuario *</label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" 
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" required>
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rol *</label>
                            <select id="role" name="role" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" required>
                                <option value="">Seleccionar rol...</option>
                                <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="user" {{ old('role')=='user' ? 'selected' : '' }}>Usuario</option>
                                <option value="client" {{ old('role')=='client' ? 'selected' : '' }}>Cliente</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition-all duration-200 ease-in-out">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const togglePasswordButton = document.getElementById('togglePassword');
    const eyeIcon = togglePasswordButton.querySelector('i'); // Obtener el icono dentro del botón

    togglePasswordButton.addEventListener('mousedown', () => {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    });

    // Volver a ocultar cuando se suelta el clic
    togglePasswordButton.addEventListener('mouseup', () => {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    });

    // También volver a ocultar si el mouse sale del botón mientras está presionado
    togglePasswordButton.addEventListener('mouseleave', () => {
        // Solo si el tipo es texto (es decir, estaba presionado)
        if (passwordInput.type === 'text') { 
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });
</script>

@endsection