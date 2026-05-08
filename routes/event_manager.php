use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'event_manager'])
->prefix('dashboard')
->name('dashboard.')
->group(function () {

Route::get('/', [App\Http\Controllers\EventManager\DashboardController::class, 'index'])->name('index');

Route::get('/events', [App\Http\Controllers\EventManager\EventController::class, 'index'])->name('events.index');
Route::get('/events/create', [App\Http\Controllers\EventManager\EventController::class, 'create'])->name('events.create');
Route::post('/events', [App\Http\Controllers\EventManager\EventController::class, 'store'])->name('events.store');

Route::get('/events/{event}/edit', [App\Http\Controllers\EventManager\EventController::class, 'edit'])->name('events.edit');
Route::patch('/events/{event}', [App\Http\Controllers\EventManager\EventController::class, 'update'])->name('events.update');

Route::patch('/events/{event}/publish', [App\Http\Controllers\EventManager\EventController::class, 'publish'])->name('events.publish');
Route::delete('/events/{event}', [App\Http\Controllers\EventManager\EventController::class, 'destroy'])->name('events.destroy');

// add others (promo, waitlist, attendees, etc)
});