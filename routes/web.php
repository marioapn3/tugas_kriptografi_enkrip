<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Models\Account;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('dashboard.index'));
});
Route::get('/testing/jurnal-umum', function () {

    $accounts = Account::all();

    foreach ($accounts as $account) {
        $totalAmount = $account->getTotalAmount();

        echo "{$account->name} (kode akun : {$account->code})";
        echo "Total Amount: {$totalAmount}  <br>";
    }
});


Route::get('/neraca', function () {


    // Mengambil akun-akun yang memiliki accountCategory dengan classification_id 4 atau 5
    $pendapatan = Account::whereHas('AccountCategory', function ($query) {
        $query->where('classification_id', 4);
    })->get();
    $beban = Account::whereHas('AccountCategory', function ($query) {
        $query->where('classification_id', 5);
    })->get();

    foreach ($pendapatan as $account) {
        $totalAmount = $account->getTotalAmount();

        echo "{$account->name} (kode akun : {$account->code})";
        echo "Total Amount: {$totalAmount}  <br>";
    }
    foreach ($beban as $account) {
        $totalAmount = $account->getTotalAmount();

        echo "{$account->name} (kode akun : {$account->code})";
        echo "Total Amount: {$totalAmount}  <br>";
    }
});


Route::prefix('admin')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'showLoginForm')->name('login');
        Route::get('success-reset', 'showSuccessResetPassword')->name('successreset');
        Route::post('login', 'login');
        Route::middleware(['auth'])->group(function () {
            Route::post('logout', 'logout')->name('logout');
        });
    });

    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('showResetForm');
        Route::post('password/reset', 'reset')->name('resetpassword');
    });

    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('password/reset', 'showLinkRequestForm')->name('showlinkrequestform');
        Route::post('password/email', 'sendResetLinkEmail')->name('sendresetlinkemail');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/', function () {
            return redirect(route('dashboard.index'));
        });

        Route::controller(DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard.index');
        });

        require __DIR__ . '/admin/contacts.php';
        require __DIR__ . '/admin/settings.php';
        require __DIR__ . '/admin/audits.php';
        require __DIR__ . '/admin/journal.php';
        require __DIR__ . '/admin/storages.php';
        require __DIR__ . '/admin/transaction/selling.php';
        require __DIR__ . '/admin/transaction/purchase.php';
        require __DIR__ . '/admin/transaction/expense.php';
        require __DIR__ . '/admin/report/general_ledger.php';
        require __DIR__ . '/admin/report/balance_sheet.php';
        require __DIR__ . '/admin/report/income_statement.php';

        Route::get('data/test', function () {
            return Inertia::render('admin/test/test1');
        })->name('test');

        Route::get('data/test2', function () {
            return Inertia::render('admin/test/test2');
        })->name('test2');
    });
});
