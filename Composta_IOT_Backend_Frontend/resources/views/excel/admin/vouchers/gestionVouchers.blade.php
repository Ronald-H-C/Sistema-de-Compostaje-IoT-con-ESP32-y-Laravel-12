@extends('admin.dashboard')

@section('content')
    <div id="layoutSidenav_content">
        @if (session('success'))
            <p class="alert alert-success">{{ session('success') }}</p>
        @endif

        <!-- Encabezado -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Gestión de Comprobantes de Pago</h1>
            <a href="{{ route('vouchers.delete') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-trash-restore-alt fa-sm text-white-50"></i> Ver Comprobantes Rechazados
            </a>
        </div>

        <!-- Tabla de Vouchers -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Comprobantes Recibidos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="vouchersTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Nro</th>
                                <th>Usuario</th>
                                <th>Plan</th>
                                <th>Imagen</th>
                                <th>Observaciónes</th>
                                <th>Estado</th>
                                <th>Fecha de Envío</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1 @endphp
                            @forelse ($vouchers as $voucher)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $voucher->user->name ?? 'N/A' }}</td>
                                    <td>{{ $voucher->plan->name ?? 'N/A' }}</td>
                                    <td>
                                        @if ($voucher->image)
                                            <a href="{{ asset('storage/' . $voucher->image) }}" target="_blank"
                                                style="color: red; text-decoration: underline;">
                                                Ver
                                                imagen
                                            </a>
                                        @else
                                            Sin imagen
                                        @endif
                                    </td>


                                    <td>{{ $voucher->observations ?? '—' }}</td>
                                    <td>
                                        @switch($voucher->state)
                                            @case(0)
                                                <span class="badge bg-warning text-dark">Rechazado</span>
                                            @break

                                            @case(1)
                                                <span class="badge bg-danger">Pendiente</span>
                                            @break

                                            @case(2)
                                                <span class="badge bg-success">Aprobado</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td>{{ $voucher->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <!-- Editar -->
                                        <a href="{{ route('vouchers.edit', $voucher->id) }}" class="btn btn-sm btn-warning"
                                            title="Actualizar estado">
                                            <i class="fas fa-edit"></i> Actualizar estado
                                        </a>

                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No hay comprobantes disponibles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $vouchers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
