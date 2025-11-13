@extends('admin.dashboard')

@section('content')
    <div id="layoutSidenav_content">
        @if (session('success'))
            <p class="alert alert-success">{{ session('success') }}</p>
        @endif

        @if (session('error'))
            <p class="alert alert-danger">{{ session('error') }}</p>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary text-white">
                <h6 class="m-0 font-weight-bold">Editar Comprobante de Pago</h6>
                <a href="{{ route('vouchers.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver al listado
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Columna izquierda -->
                        <div class="col-md-12 text-center">
                            <label class="form-label fw-bold">
                                Usuario:
                                <span class="form-control-plaintext d-inline ms-2">
                                    {{ $voucher->user->name }} {{ $voucher->user->firstLastName }}
                                </span>
                            </label>
                        </div>


                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="observations" class="form-label">Observaciónes</label>
                                <textarea name="observations" id="observations" class="form-control" rows="3">{{ old('notes', $voucher->observations) }}</textarea>
                            </div>
                        </div>

                        <!-- Columna derecha -->
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label for="state" class="form-label">Estado *</label>
                                <select class="form-select" id="state" name="state" required>
                                    <option value="">Seleccionar estado...</option>
                                    <option value="0" {{ old('state', $voucher->state) == 0 ? 'selected' : '' }}>
                                        Rechazado</option>
                                    <option value="1" {{ old('state', $voucher->state) == 1 ? 'selected' : '' }}>
                                        Pendiente</option>
                                    <option value="2" {{ old('state', $voucher->state) == 2 ? 'selected' : '' }}>
                                        Aprobado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
