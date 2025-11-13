<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FertilizerController;
use App\Http\Controllers\FertilizerAdminController;
use App\Http\Controllers\PaymentVoucherController;
use App\Http\Controllers\PlanChangeRequestController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserReferenceController;
use App\Http\Controllers\AdminPlanController;
use App\Http\Controllers\UserPlanController;
use App\Http\Controllers\PaymentUserVoucherController;
use App\Http\Controllers\MaterialController;
use Illuminate\Http\Request;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PrototypeController;

Route::get('/', [PageController::class, 'index'])->name('index');

Route::post('/registro', [ClientController::class, 'registro'])->name('client.registro');
Route::get('/registro', function () {
    return view('registro');
})->name('registro');

Route::get('/productos-usuario/{id}', [PageController::class, 'productsByUser'])->name('products.userProducts');
// Otras vistas
Route::get('/proyecto', [PageController::class, 'proyecto'])->name('proyecto');
Route::get('/nosotros', [PageController::class, 'nosotros'])->name('nosotros');
Route::post('/contactar', [PageController::class, 'store'])->name('contact.store');
Route::get('/contactanos', [PageController::class, 'contactanos'])->name('contactanos');

Route::get('/mapa', [MapController::class, 'ver'])->name('mapa.ver');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Página protegida
/*Route::middleware('auth')->group(function () {
    // Rutas aquí dentro solo son accesibles si el usuario está logueado
    Route::get('/dashboard', function (){
        return view('dashboard');
    });
});*/
Route::middleware(['auth'])->group(function () {
    Route::get('/payment/', [PaymentController::class, 'showForm'])->name('payment.form');
    Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/pago/recibo/{sale}', [PaymentController::class, 'mostrarRecibo'])->name('payment.receipt');
    Route::get('/sales/pdf/{sale}', [PaymentController::class, 'descargarPDFVenta'])
    ->name('user.sale.receipt');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboardAdmin'])->name('dashboardAdmin');

    Route::get('/getUserG', [AdminController::class, 'dashboardAdminG'])->name('gU');

    Route::get('/crear', function () {
        return view('create');
    })->name('verCrear');

    Route::get('/dashboardA', function () {
        return view('admin.dashboard');
    })->name('dash');

    Route::resource('products', FertilizerAdminController::class);
    Route::get('productos-eliminados', [FertilizerAdminController::class, 'delete'])->name('products.delete');
    Route::put('productos/{id}/activar', [FertilizerAdminController::class, 'activate'])->name('products.activate');

    // CRUD tradicional para planes(Route::resource(...) solo registra 7 rutas estándar:index, create, store, show, edit, update, destroy)
    Route::resource('plans', PlanController::class); // ✅ CORRECTO
    Route::get('planes-eliminados', [PlanController::class, 'delete'])->name('plans.delete');
    Route::put('planes/{id}/activar', [PlanController::class, 'activate'])->name('plans.activate');

    // CRUD tradicional para change_plans(Route::resource(...) solo registra 7 rutas estándar:index, create, store, show, edit, update, destroy)
    Route::resource('change_plans', PlanChangeRequestController::class); // ✅ CORRECTO
    Route::get('change_plans-eliminados', [PlanChangeRequestController::class, 'delete'])->name('change_plans.delete');
    Route::put('change_plans/{id}/activar', [PlanChangeRequestController::class, 'activate'])->name('change_plans.activate');
    // CRUD tradicional para contacts(Route::resource(...) solo registra 7 rutas estándar:index, create, store, show, edit, update, destroy)
    Route::resource('contacts', ContactMessageController::class);
    Route::get('contact-deleted', [ContactMessageController::class, 'delete'])->name('contacts.delete');
    Route::put('contact/{id}/activate', [ContactMessageController::class, 'activate'])->name('contacts.activate');

    Route::resource('user_references', UserReferenceController::class);
    Route::resource('user_plans', AdminPlanController::class);
    Route::resource('admins', AdminController::class);
    Route::get('/getUserGe', [AdminController::class, 'index'])->name('admin.gestionUser');
    Route::get('/cambiarCA', [AdminController::class, 'cambiarContrasenia'])->name('cambiarCA');
    Route::post('/actualizarC', [AdminController::class, 'actualizarContrasenia'])->name('contrasenia');
    Route::get('/materials-compostaje', [MaterialController::class, 'index1'])->name('materials.index');
   Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');

// Actualizar un material existente
Route::put('/materials/{material}', [MaterialController::class, 'update'])->name('materials.update');
    Route::get('/materials/create', [MaterialController::class, 'create'])->name('materials.create');

// Formulario para editar un material existente
Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
Route::get('/compostadores', [PrototypeController::class, 'index'])->name('compost');
Route::post('/prototypes/assign-user', [PrototypeController::class, 'assignUser'])
     ->name('prototype.assignUser');
Route::get('/inactivar/{id}/{status}', [PrototypeController::class, 'desactive'])
     ->name('desactive');

Route::get('/createPrototype', [PrototypeController::class, 'create'])
     ->name('createPrototype');
     
Route::delete('/compostadores/{prototype}', [PrototypeController::class, 'desactive'])
     ->name('prototypes.destroy');

Route::post('/storePrototype', [PrototypeController::class, 'store'])
     ->name('storePrototype');
    
});

// Rutas para usuario común
Route::middleware(['auth', 'role:user'])->group(function () {
    //Route::get('/user/dashboard', function () {
        //return view('user.dashboard');
    //});
    
    Route::get('/user/dashboard', [DashboardController::class, 'datos'])->name('gUs');
    Route::get('/user/historial', [DashboardController::class, 'paginacionDatos'])->name('historial');
    Route::get('/dashboard/latest-json', [DashboardController::class, 'getLatestJsonData'])->name('dashboard.latestData');
    Route::post('/destacado/{id}', [FertilizerController::class, 'starw'])->name('destacado');
    Route::get('/dashboard/datos-json', [DashboardController::class, 'datosJson'])->name('dashboard.datosJson');
    Route::get('/cambiarCU', [UserController::class, 'cambiarContrasenia'])->name('cambiarCU');
    Route::post('/actualizarC', [UserController::class, 'actualizarContrasenia'])->name('contrasenia');
    Route::get('/verProductos', [FertilizerController::class, 'index'])->name('vista');
    Route::resource('fertilizers', FertilizerController::class);
    Route::resource('sales', SaleController::class);
    Route::view('/materiales', 'user.education.materials')->name('materials');
    Route::view('/tips', 'user.education.tips')->name('tips');
    Route::resource('uplans', UserPlanController::class);
    Route::get('/planes/comprar/{id}', [UserPlanController::class, 'comprar'])->name('uplans.comprar');
    Route::post('/planes/procesar-pago/{id}', [UserPlanController::class, 'procesarPago'])->name('planes.pago');
    Route::get('/pago/recibo/{plan}/{user}', [UserPlanController::class, 'mostrarRecibo'])->name('mostrar');
    Route::get('/pago/pdf/{id}', [UserPlanController::class, 'descargarPDF'])->name('payment.download');
    Route::get('/deploy', [PaymentUserVoucherController::class, 'index'])->name('deployC');
    Route::get('/comprobante/{id}', [PaymentUserVoucherController::class, 'edit'])->name('editVoucher');
    Route::get('/delete/{id}', [PaymentUserVoucherController::class, 'deleteVoucher'])->name('deleteVoucher');
    Route::put('/update/{id}', [PaymentUserVoucherController::class, 'update'])->name('updateC');
    Route::get('/deletes', [PaymentUserVoucherController::class, 'delete'])->name('deleteC');
    Route::get('/materiales-compostaje', [MaterialController::class, 'index1'])->name('materiales.index');
    Route::post('/materiales/filtrar', [MaterialController::class, 'filtrar'])->name('materials.filter');
    Route::get('/proporciones', function () {
    return view('user.education.proporciones');
})->name('proporciones');


   
    Route::post('/ver-detalle', function (Request $request) {
        $clasification = $request->input('clasification');

        return match($clasification) {
            'verde' => view('user.education.verde'),
            'marron' => view('user.education.marron'),
            'no_compostable' => view('user.education.no_compostable'),
            default => back()->with('error', 'Clasificación desconocida.')
        };
    })->name('verDetalle');


    Route::get('/select', function () {
        return view('user.report.select');
    })->name('select');

    // Reporte de lecturas de compostaje
    Route::get('/reportes/lecturas', [ReportController::class, 'lecturas'])->name('reportes.lecturas');

    // Reporte de lecturas de sensores (con filtro por día, semana o mes)
    Route::get('/reportes/lecturas', [ReportController::class, 'filter'])->name('reportes.lecturas');

    // (Opcional) Reporte de ventas de composta
    Route::get('/reportes/ventas', [ReportController::class, 'sales'])->name('reportes.ventas');

    Route::get('/reports/download', [ReportController::class, 'downloadPdf'])
    ->name('reports.download');

    Route::get('/reports/downloade', [ReportController::class, 'descargarReporte'])
    ->name('reportes.descargar');
    
    Route::get('/rechazados', function () {
    return view('user.voucher.delete');
})->name('rechazadosU');

    Route::get('/reportes/historialVentas', [ReportController::class, 'historialV'])->name('historialV');
    Route::get('/reportes/historialLecturas', [ReportController::class, 'historialL'])->name('historialL');
    Route::get('/vistaPlans', [UserPlanController::class, 'showAdqPlans'])->name('showPlansAdq');


});



Route::middleware(['auth', 'role:client'])->group(function () {
     Route::get('/client/dashboard', function () {
        return redirect()->route('index'); // Redirige a la vista index.blade.php
    });

    Route::post('/cart/add', [SaleController::class, 'add'])->name('cart.add');
    Route::get('/cart', [SaleController::class, 'index1'])->name('cart.index');
    Route::post('/cart/remove', [SaleController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/cancel', [SaleController::class, 'cancel'])->name('cart.cancel');

    Route::post('/checkout', [PaymentController::class, 'showForm'])->name('show.Form');
    Route::get('/shopClient', [PaymentController::class, 'shop'])->name('shop');
});
Route::put('/profile/update', [UserReferenceController::class, 'updateProfile'])->name('profile.update')->middleware('auth');
Route::post('/references/store', [UserReferenceController::class, 'addressReferences'])->name('references.store')->middleware('auth');