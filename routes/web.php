<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TrainingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\OutputController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//ICカードテスト
Route::get('/api/readtest', [AttendanceController::class, 'test']);

//ICカードテスト本実装、出席情報の作成
Route::post('/api/attendance', [AttendanceController::class, 'attendance'])->name('attendance');

//ICリーダー停止の処理
Route::post('/api/cancel', [AttendanceController::class, 'cancel'])->name('cancel');

// 研修グループ一覧の取得（home画面表示用）
Route::get('/', [TrainingController::class, 'index'])->name('home');

//職員の取得と更新
Route::post('/userupdate', [UserController::class, 'updateData'])->name('userupdate');

//エクセルに出席者を出力
Route::post('/createExcel', [OutputController::class, 'createExcel'])->name('createExcel');

//以下、画面遷移
Route::get('/output', function(){
    return view('output');
})->name('output');

Route::get('/update',function(){
    return view('update');
})->name('go-update');


