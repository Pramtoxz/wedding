<?php

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GiftController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\Admin\WeddingController;
use App\Http\Controllers\Admin\WeddingEventController;
use App\Http\Controllers\Admin\WeddingGalleryController;
use App\Http\Controllers\Admin\WeddingGiftController;
use App\Http\Controllers\Admin\WeddingGuestController;
use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::get('/invitation/{slug}', [InvitationController::class, 'show'])->name('invitation.show');
Route::post('/invitation/{slug}/rsvp', [InvitationController::class, 'storeRsvp'])->name('invitation.rsvp');
Route::post('/invitation/{slug}/wish', [InvitationController::class, 'storeWish'])->name('invitation.wish');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('weddings', WeddingController::class);
        Route::get('weddings/{wedding}/whatsapp', [WeddingController::class, 'getWhatsappLink'])->name('weddings.whatsapp');

        Route::get('events', [EventController::class, 'index'])->name('events.index');
        Route::get('galleries', [GalleryController::class, 'index'])->name('galleries.index');
        Route::get('guests', [GuestController::class, 'index'])->name('guests.index');
        Route::get('gifts', [GiftController::class, 'index'])->name('gifts.index');

        Route::post('weddings/{wedding}/events', [WeddingEventController::class, 'store'])->name('weddings.events.store');
        Route::put('weddings/{wedding}/events/{event}', [WeddingEventController::class, 'update'])->name('weddings.events.update');
        Route::delete('weddings/{wedding}/events/{event}', [WeddingEventController::class, 'destroy'])->name('weddings.events.destroy');

        Route::post('weddings/{wedding}/galleries', [WeddingGalleryController::class, 'store'])->name('weddings.galleries.store');
        Route::put('weddings/{wedding}/galleries/{gallery}', [WeddingGalleryController::class, 'update'])->name('weddings.galleries.update');
        Route::delete('weddings/{wedding}/galleries/{gallery}', [WeddingGalleryController::class, 'destroy'])->name('weddings.galleries.destroy');

        Route::post('weddings/{wedding}/guests', [WeddingGuestController::class, 'store'])->name('weddings.guests.store');
        Route::put('weddings/{wedding}/guests/{guest}', [WeddingGuestController::class, 'update'])->name('weddings.guests.update');
        Route::delete('weddings/{wedding}/guests/{guest}', [WeddingGuestController::class, 'destroy'])->name('weddings.guests.destroy');
        Route::get('weddings/{wedding}/guests/{guest}/whatsapp', [WeddingGuestController::class, 'sendWhatsapp'])->name('weddings.guests.whatsapp');

        Route::post('weddings/{wedding}/gifts', [WeddingGiftController::class, 'store'])->name('weddings.gifts.store');
        Route::put('weddings/{wedding}/gifts/{gift}', [WeddingGiftController::class, 'update'])->name('weddings.gifts.update');
        Route::delete('weddings/{wedding}/gifts/{gift}', [WeddingGiftController::class, 'destroy'])->name('weddings.gifts.destroy');
    });
});

require __DIR__.'/settings.php';

