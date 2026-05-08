use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])
->prefix('admin')
->name('admin.')
->group(function () {

Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

Route::get('/managers', [App\Http\Controllers\Admin\ManagerController::class, 'index'])->name('managers');

Route::patch('/managers/{user}/suspend', [App\Http\Controllers\Admin\ManagerController::class, 'suspend'])->name('managers.suspend');

Route::get('/events', [App\Http\Controllers\Admin\EventController::class, 'index'])->name('events');

Route::get('/transactions', [App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions');
});