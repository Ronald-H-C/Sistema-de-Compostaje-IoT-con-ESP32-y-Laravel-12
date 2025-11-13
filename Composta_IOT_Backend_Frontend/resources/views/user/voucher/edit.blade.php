@extends('user.dashboard')

@section('content')
<div id="layoutSidenav_content" class="p-6">

    <!-- Mensajes -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Card principal -->
    <div class="card shadow border-secondary">
        <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white">
            <h5 class="mb-0 fw-bold">✏️ Editar Comprobante de Pago</h5>
            <a href="{{ route('deployC') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>

        <div class="card-body bg-light">
            <form action="{{ route('updateC', $voucher->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Errores de validación -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">
                    <!-- Usuario -->
                    <div class="col-12 text-center mb-3">
                        <label class="form-label fw-bold">Usuario:</label>
                        <div class="form-control-plaintext border rounded p-2 mt-1 bg-white">
                            {{ $voucher->user->name }} {{ $voucher->user->firstLastName }}
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="col-md-6">
                        <label for="state" class="form-label fw-semibold">Estado *</label>
                        <select class="form-select" id="state" name="state" required>
                            <option value="">Seleccionar estado...</option>
                            <option value="0" {{ old('state', $voucher->state) == 0 ? 'selected' : '' }}>Pendiente</option>
                            <option value="1" {{ old('state', $voucher->state) == 1 ? 'selected' : '' }}>Aprobado</option>
                            <option value="2" {{ old('state', $voucher->state) == 2 ? 'selected' : '' }}>Rechazado</option>
                        </select>
                    </div>
                </div>

                <!-- Botón de guardar -->
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
