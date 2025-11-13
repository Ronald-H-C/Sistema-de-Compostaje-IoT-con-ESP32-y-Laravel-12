<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Íconos Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-100 via-green-100 to-lime-50">

    <!-- Tarjeta de login -->
    <div class="w-full max-w-md p-8 bg-white shadow-lg rounded-2xl border border-green-100">
        <h2 class="text-2xl font-bold text-green-700 mb-6 text-center">
            <i class="fas fa-leaf mr-2 text-green-600"></i>Iniciar Sesión
        </h2>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-green-700 font-medium mb-1">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                    placeholder="tucorreo@ejemplo.com"
                    required
                >
            </div>

            <!-- Contraseña con ojito -->
            <div>
                <label for="password" class="block text-green-700 font-medium mb-1">Contraseña</label>
                <div class="relative">
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="w-full px-4 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 pr-10"
                        placeholder="••••••••"
                        required
                    >
                    <button 
                        type="button"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                        onmousedown="togglePassword('password', true)" 
                        onmouseup="togglePassword('password', false)" 
                        onmouseleave="togglePassword('password', false)"
                        ontouchstart="togglePassword('password', true)" 
                        ontouchend="togglePassword('password', false)"
                    >
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Botón ingresar -->
            <button 
                type="submit"
                class="w-full py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200"
            >
                <i class="fas fa-sign-in-alt mr-2"></i>Ingresar
            </button>
        </form>

        
       
<br>

        <!-- Botón volver al inicio -->
        <button 
            onclick="window.location='{{ url('/') }}'" 
            class="w-full py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Inicio
        </button>
         <div class="text-center text-sm mt-4 text-green-700">
                ¿No tienes una cuenta?
            </div>

            <div class="mt-2">
                <a href="{{ route('registro') }}"
                    class="w-full inline-block text-center bg-white text-green-700 font-semibold border border-green-600 py-2 px-4 rounded-xl hover:bg-green-50 transition duration-300 shadow">
                    Registrarse
                </a>
            </div>
    </div>

    

<script>
    function togglePassword(id, show) {
        const input = document.getElementById(id);
        input.type = show ? 'text' : 'password';
    }
</script>

</body>
</html>
