@extends('admin.dashboard')

@section('content')
    <div id="layoutSidenav_content" class="p-6 bg-gray-50 min-h-screen">
        {{-- Alertas Refinadas (sin JS) --}}
        @if (session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-300 text-green-800 font-medium shadow-sm flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600"></i>{{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-300 text-red-800 font-medium shadow-sm flex items-center">
                <i class="fas fa-exclamation-circle mr-2 text-red-600"></i>{{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white flex justify-between items-center p-5">
                <h5 class="m-0 font-semibold text-lg flex items-center">
                    <i class="fas fa-user-edit mr-2"></i>Editar Referencia de Usuario
                </h5>
                <a href="{{ route('user_references.index') }}" 
                   title="Volver al listado"
                   class="inline-flex items-center justify-center h-11 w-11 rounded-full text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                    <i class="fas fa-long-arrow-alt-left text-2xl"></i>
                </a>
            </div>

            <div class="bg-white p-6">
                {{-- Validación de errores (Estilo Tailwind) --}}
                @if ($errors->any())
                    <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-300 text-red-900 shadow-sm">
                        <h6 class="font-semibold mb-2 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Errores encontrados:
                        </h6>
                        <ul class="mb-0 list-disc list-inside text-sm text-red-800">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user_references.update', $user_reference->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                            <div class="flex items-center w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-gray-700 font-medium">
                                <i class="fas fa-user mr-2 text-green-600"></i>
                                {{ $user_reference->user->name }} {{ $user_reference->user->firstLastName ?? '' }}
                            </div>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono <span class="text-red-500">*</span></label>
                            <div class="relative rounded-lg shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="text" name="phone" id="phone" 
                                       class="block w-full rounded-lg border-gray-300 py-2.5 pl-10 focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                       value="{{ old('phone', $user_reference->phone) }}" placeholder="Ej: +591 77777777">
                            </div>
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Correo de contacto</label>
                            <div class="relative rounded-lg shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="contact_email" id="contact_email" 
                                       class="block w-full rounded-lg border-gray-300 py-2.5 pl-10 focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                       value="{{ old('contact_email', $user_reference->contact_email) }}" placeholder="correo@ejemplo.com">
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label for="whatsapp_link" class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                                <input type="url" name="whatsapp_link" id="whatsapp_link" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5"
                                       value="{{ old('whatsapp_link', $user_reference->whatsapp_link) }}" placeholder="https://wa.me/...">
                            </div>
                            <div>
                                <label for="facebook_link" class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                                <input type="url" name="facebook_link" id="facebook_link" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5"
                                       value="{{ old('facebook_link', $user_reference->facebook_link) }}" placeholder="https://facebook.com/...">
                            </div>
                            <div>
                                <label for="instagram_link" class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                                <input type="url" name="instagram_link" id="instagram_link" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5"
                                       value="{{ old('instagram_link', $user_reference->instagram_link) }}" placeholder="https://instagram.com/...">
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label for="youtube_link" class="block text-sm font-medium text-gray-700 mb-1">YouTube</label>
                                <input type="url" name="youtube_link" id="youtube_link" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5"
                                       value="{{ old('youtube_link', $user_reference->youtube_link) }}" placeholder="https://youtube.com/...">
                            </div>
                            <div>
                                <label for="tiktok_link" class="block text-sm font-medium text-gray-700 mb-1">TikTok</label>
                                <input type="url" name="tiktok_link" id="tiktok_link" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2.5"
                                       value="{{ old('tiktok_link', $user_reference->tiktok_link) }}" placeholder="https://tiktok.com/...">
                            </div>
                            <div>
                                <label for="qr_image" class="block text-sm font-medium text-gray-700 mb-1">Código QR (opcional)</label>
                                <input type="file" name="qr_image" id="qr_image" 
                                       class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none
                                              file:py-2.5 file:px-4 file:mr-4 file:border-0
                                              file:bg-gray-50 file:font-medium file:text-gray-700 hover:file:bg-gray-100">
                                
                                @if ($user_reference->qr_image)
                                    <div class="mt-3">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">QR actual:</label>
                                        {{-- Previsualización estilizada --}}
                                        <img src="{{ asset($user_reference->qr_image) }}" 
                                             alt="QR actual" class="rounded-lg border border-gray-200 shadow-sm w-32 h-32 object-cover">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition-all duration-200 ease-in-out">
                            <i class="fas fa-save mr-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection