<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-100 to-green-300 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-8 w-full max-w-md border border-green-300">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-green-800 text-center mb-6">Crear una cuenta</h2>

        {{-- Éxito --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm sm:text-base">
                {{ session('success') }}
            </div>
        @endif

        {{-- Errores generales --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm sm:text-base">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('registro') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-green-700 font-semibold mb-1 text-sm sm:text-base">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-green-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 shadow-sm text-sm sm:text-base">
            </div>

            <div class="flex flex-col sm:flex-row sm:space-x-2">
                <div class="flex-1 mb-2 sm:mb-0">
                    <label class="block text-green-700 font-semibold mb-1 text-sm sm:text-base">Apellido Paterno</label>
                    <input type="text" name="firstLastName" value="{{ old('firstLastName') }}" required
                        class="w-full px-4 py-2 border border-green-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 shadow-sm text-sm sm:text-base">
                </div>
                <div class="flex-1">
                    <label class="block text-green-700 font-semibold mb-1 text-sm sm:text-base">Apellido Materno</label>
                    <input type="text" name="secondLastName" value="{{ old('secondLastName') }}"
                        class="w-full px-4 py-2 border border-green-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 shadow-sm text-sm sm:text-base">
                </div>
            </div>

            <div>
                <label class="block text-green-700 font-semibold mb-1 text-sm sm:text-base">Nombre de usuario</label>
                <input type="text" name="username" value="{{ old('username') }}" required
                    class="w-full px-4 py-2 border border-green-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 shadow-sm text-sm sm:text-base">
            </div>

            <div>
                <label class="block text-green-700 font-semibold mb-1 text-sm sm:text-base">Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-green-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 shadow-sm text-sm sm:text-base">
            </div>

            <div>
                <label class="block text-green-700 font-semibold mb-1 text-sm sm:text-base">Contraseña</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border border-green-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 shadow-sm text-sm sm:text-base">
            </div>

            <div>
                <label class="block text-green-700 font-semibold mb-1 text-sm sm:text-base">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-2 border border-green-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 shadow-sm text-sm sm:text-base">
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl transition duration-300 shadow text-sm sm:text-base">
                    Registrarse
                </button>
            </div>

            <div class="text-center text-sm sm:text-base mt-4 text-green-700">
                ¿Ya tienes una cuenta?
            </div>

            <div class="mt-2">
                <a href="{{ route('login') }}"
                    class="w-full inline-block text-center bg-white text-green-700 font-semibold border border-green-600 py-2 px-4 rounded-xl hover:bg-green-50 transition duration-300 shadow text-sm sm:text-base">
                    Iniciar sesión
                </a>
            </div>
        </form>
    </div>

</body>
</html>
