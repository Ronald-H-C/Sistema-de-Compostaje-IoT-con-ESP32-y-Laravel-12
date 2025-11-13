<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AlertController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/dashboard/type_alert', [DashboardController::class, 'getTypeAlert']);
Route::post('/dashboard/store', [DashboardController::class, 'store']);
Route::get('/dashboard/prueba', [DashboardController::class, 'prueba']);
Route::get('/get_last_reading', [UserController::class, 'getLastReadingApi']);
Route::get('/get_historical_readings', [UserController::class, 'getHistoricalReadingsApi']);
Route::post('/update_user', [UserController::class, 'updateUserApi']);
Route::post('/login', [UserController::class, 'loginApi']);
Route::get('/get_user_by_id', [UserController::class, 'getUserByIdApi']);

Route::get('/export_readings_pdf', [ReportController::class, 'exportReadingsPDF']);
Route::get('/export_readings_xlsx', [ReportController::class, 'exportReadingsXLSX']);

Route::get('/export_sales_pdf', [ReportController::class, 'exportSalesPDF']);
Route::get('/export_sales_xlsx', [ReportController::class, 'exportSalesXLSX']);

Route::get('/mobile_reports/download_sales', [ReportController::class, 'downloadPdf']);
Route::get('/mobile_reports/download_readings', [ReportController::class, 'descargarReporte']);


Route::get('/materials', [MaterialController::class, 'index']);
Route::get('/materials/{material}', [MaterialController::class, 'show']);

Route::get('/get_alerts', [AlertController::class, 'getAlerts']);

Route::post('/updateTypeAlert', [AlertController::class, 'updateTypeAlert']);

Route::get('/get_Type_Alert', [DashboardController::class, 'getTypeAlert']);
