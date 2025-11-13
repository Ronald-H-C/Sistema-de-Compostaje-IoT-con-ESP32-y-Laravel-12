@extends('admin.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">

    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-300 text-green-800 font-medium shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-2 text-green-600"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-300 text-red-800 font-medium shadow-sm flex items-center">
            <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white flex justify-between items-center p-5">
            <h5 class="m-0 font-semibold text-lg flex items-center">
                {{-- Icono y Título actualizados --}}
                <i class="fas fa-user-friends mr-2"></i>
                Crear Referencia de Usuario
            </h5>
            
            <a href="{{ route('user_references.index') }}" 
               title="Volver al listado"
               class="inline-flex items-center justify-center h-11 w-11 rounded-full text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                <i class="fas fa-long-arrow-alt-left text-2xl"></i>
            </a>
        </div>

        <div class="bg-white p-6">
            <form action="{{ route('user_references.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

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

                {{-- Grid con más espaciado --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="space-y-5">
                        <div>
                            <label for="idUser" class="block text-sm font-medium text-gray-700 mb-1">Usuario *</label>
                            <select name="idUser" id="idUser" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" required>
                                <option value="">Seleccionar usuario...</option>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('idUser') == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }} {{ $usuario->firstLastName ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text" name="phone" id="phone" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" value="{{ old('phone') }}">
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Correo de contacto</Lavel>
                            <input type="email" name="contact_email" id="contact_email" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" value="{{ old('contact_email') }}">
                        </div>

                        <div>
                            <label for="whatsapp_link" class="block text-sm font-medium text-gray-700 mb-1">Enlace de WhatsApp</label>
                            <input type="url" name="whatsapp_link" id="whatsapp_link" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" value="{{ old('whatsapp_link') }}">
                        </div>

                        <div>
                            <label for="facebook_link" class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                            <input type="url" name="facebook_link" id="facebook_link" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" value="{{ old('facebook_link') }}">
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label for="instagram_link" class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                            <input type="url" name="instagram_link" id="instagram_link" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" value="{{ old('instagram_link') }}">
                        </div>

                        <div>
                            <label for="youtube_link" class="block text-sm font-medium text-gray-700 mb-1">YouTube</label>
                            <input type="url" name="youtube_link" id="youtube_link" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" value="{{ old('youtube_link') }}">
                        </div>

                        <div>
                            <label for="tiktok_link" class="block text-sm font-medium text-gray-700 mb-1">TikTok</label>
                            <input type="url" name="tiktok_link" id="tiktok_link" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5" value="{{ old('tiktok_link') }}">
                        </div>

                        <div>
                            <label for="qr_image" class="block text-sm font-medium text-gray-700 mb-1">Código QR</label>
                            {{-- Input de archivo estilizado con Tailwind y pseudo-clases 'file:' --}}
                            <input type="file" name="qr_image" id="qr_image" 
                                   class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none
                                          file:py-2.5 file:px-4 file:mr-4 file:border-0
                                          file:bg-gray-50 file:font-medium file:text-gray-700 hover:file:bg-gray-100">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition-all duration-200 ease-in-out">
                        <i class="fas fa-save mr-2"></i> Guardar Referencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection