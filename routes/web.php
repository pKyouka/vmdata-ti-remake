<?php
// routes/web.php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VMController;
use App\Http\Controllers\VMRentalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard umum (hanya login)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// ROUTE ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Server hanya bisa dikelola admin
    Route::resource('servers', ServerController::class);

    // Rental full akses (admin bisa kelola semua)
    Route::resource('rentals', RentalController::class);

    // Admin dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Admin settings
    Route::get('/admin/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])
        ->name('admin.settings.index');
    Route::get('/admin/settings/{setting}/edit', [\App\Http\Controllers\Admin\SettingController::class, 'edit'])
        ->name('admin.settings.edit');
    Route::put('/admin/settings/{setting}', [\App\Http\Controllers\Admin\SettingController::class, 'update'])
        ->name('admin.settings.update');

    // Admin Reports
    Route::get('/admin/reports', function () {
        return view('admin.reports.index');
    })->name('admin.reports.index');

    Route::get('/admin/reports/rental', function () {
        // Simple controller-less demo; in real usage, use a controller to prepare $rows, $summary, $period
        $rows = \App\Models\VMRental::with(['user', 'vm', 'vm.category'])->latest()->take(200)->get();
        $summary = [
            'total_rentals' => $rows->count(),
            'active_vms' => $rows->where('status', 'active')->count(),
            'completed' => $rows->where('status', 'completed')->count(),
            'unique_users' => $rows->pluck('user_id')->unique()->count(),
        ];
        return view('admin.reports.rental', compact('rows', 'summary'));
    })->name('admin.reports.rental');

    Route::get('/admin/reports/vm', function () {
        $rows = \App\Models\VM::with(['specification', 'currentRental.user'])->latest()->take(200)->get();
        $summary = [
            'total_vms' => $rows->count(),
            'running' => $rows->where('status', 'running')->count(),
            'stopped' => $rows->where('status', 'stopped')->count(),
            'owners' => $rows->pluck('currentRental.user_id')->unique()->count(),
        ];
        return view('admin.reports.vm', compact('rows', 'summary'));
    })->name('admin.reports.vm');

    // Admin notifications
    Route::get('/admin/notifications', function () {
        $user = auth()->user();
        // When admin opens the notifications page, mark unread notifications as read automatically
        try {
            $user->unreadNotifications->markAsRead();
        } catch (\Exception $e) {
            // ignore if anything goes wrong with marking as read
        }

        $notifications = $user->notifications()->latest()->get();
        return view('admin.notifications.index', compact('notifications'));
    })->name('admin.notifications.index');

    // Delete a single notification
    Route::post('/admin/notifications/{id}/read', function ($id) {
        $user = auth()->user();
        $note = $user->notifications()->where('id', $id)->first();
        if ($note) {
            // delete the notification record
            $note->delete();
        }
        return redirect()->back()->with('success', 'Notifikasi telah dihapus.');
    })->name('admin.notifications.read');

    // Clear all notifications for admin (delete all)
    Route::post('/admin/notifications/clear', function () {
        $user = auth()->user();
        try {
            $user->notifications()->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membersihkan notifikasi.');
        }
        return redirect()->back()->with('success', 'Semua notifikasi telah dibersihkan.');
    })->name('admin.notifications.clear');

    // Admin respond to VMRental requests
    Route::post('/admin/vmrentals/{id}/respond', [\App\Http\Controllers\VMRentalController::class, 'respond'])
        ->name('admin.vmrentals.respond');

    // Admin: update Rental status inline
    Route::post('/rentals/{rental}/status', [RentalController::class, 'updateStatus'])
        ->name('rentals.updateStatus');

    // Admin: update VMRental status inline
    Route::post('/vmrentals/{vmrental}/status', [VMRentalController::class, 'updateStatus'])
        ->name('vmrentals.updateStatus');

    // Admin: update VM status (used by inline admin status change)
    Route::post('/vms/{vm}/status', [VMController::class, 'updateStatus'])
        ->name('vms.updateStatus');
});

// ROUTE USER (accessible by admin and user)
Route::middleware(['auth', 'role:admin,user'])->group(function () {
    // User hanya bisa kelola VM sendiri
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    // Ensure named index route exists (some callers expect route('vms.index'))
    Route::get('/vms', [VMController::class, 'index'])->name('vms.index');
    // Ensure named create route exists for forms/links
    Route::get('/vms/create', [VMController::class, 'create'])->name('vms.create');
    Route::resource('vms', VMController::class);

    // User dashboard
    Route::get('/user/dashboard', [DashboardController::class, 'user'])
        ->name('user.dashboard');
});


// ROUTE UMUM (semua user login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Test route - tambahkan di bagian bawah
    Route::get('/test-role', function () {
        return 'Role middleware working! User: ' . auth()->user()->name . ', Role: ' . auth()->user()->role;
    })->middleware(['auth', 'role:user']);

    Route::post('/vmrentals/{id}/request-reset', [VMRentalController::class, 'requestReset'])->name('vmrentals.requestReset');
    Route::resource('vmrentals', VMRentalController::class);
});

require __DIR__ . '/auth.php';