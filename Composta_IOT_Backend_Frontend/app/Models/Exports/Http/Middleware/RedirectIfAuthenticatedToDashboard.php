<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedToDashboard
{
    public function handle(Request $request, Closure $next)
    {
        $excludedRoutes = [
            'login',
            'registro',
            'logout',
            'deploy',

            // Ejemplo de rutas con parámetros usando *
            'productos-usuario/*',
            'proyecto',
            'nosotros',
            'contactar',
            'contactanos',
            'mapa',

            // Admin
            'admin/dashboard',
            'getUserG',
            'crear',
            'dashboardA',
            'products*',
            'productos-eliminados',
            'productos/*/activar',
            'plans*',
            'planes-eliminados',
            'planes/*/activar',
            'change_plans*',
            'change_plans-eliminados',
            'change_plans/*/activar',
            'contacts*',
            'contact-deleted',
            'contact/*/activate',
            'user_references*',
            'user_plans*',
            'admins*',
            'getUserGe',
            'cambiarCA',
            'actualizarC',

            // Usuario
            'user/dashboard',
            'user/historial',
            'destacado/*',
            'dashboard/datos-json',
            'cambiarCU',
            'actualizarC',
            'verProductos',
            'fertilizers*',
            'sales*',
            'materiales',
            'tips',
            'uplans*',
            'planes/comprar/*',
            'planes/procesar-pago/*',
            'pago/recibo/*',
            'pago/pdf/*',
            'comprobante/*',
            'update/*',
            'deletes',
            'materiales-compostaje',
            'materiales/filtrar',
            'proporciones',
            'ver-detalle',
            'select',
            'reportes/lecturas',
            'reportes/ventas',
            'reports/download',
            'reports/downloade',
            'rechazados',

            // Cliente
            'client/dashboard',

            // Formularios y referencias
            'profile/update',
            'references/store',

            // Pagos
            'payment/*',
        ];


        foreach ($excludedRoutes as $routePattern) {
            if ($request->routeIs($routePattern)) {
                return $next($request);
            }
        }

        if (Auth::check()) {
            $user = Auth::user();

            // Redirigir según el rol
            $redirectRoute = match($user->role) {
                'admin' => '/admin/dashboard',
                'user' => '/user/dashboard',
                'client' => '/client/dashboard',
                default => '/dashboard',
            };

            
        }

        return $next($request);
    }
}

