<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SongLikeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/album/{albumName}', [HomeController::class, 'showAlbum'])->name('album.show');

Route::get('/artist/{artistName}', [HomeController::class, 'showArtist'])->name('artist.show');

Route::middleware(['auth', 'verified'])->group(function () {
    // Rute Dashboard & Lagu kita
    Route::get('/dashboard', [SongController::class, 'index'])->name('dashboard');
    Route::get('/upload', [SongController::class, 'create'])->name('songs.create');
    Route::post('/upload', [SongController::class, 'store'])->name('songs.store');

    // --- TAMBAHKAN 3 BARIS INI KEMBALI ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk MENAMPILKAN form edit
    Route::get('/songs/{song}/edit', [SongController::class, 'edit'])->name('songs.edit');

    // Rute untuk MEMPROSES/MENYIMPAN perubahan (kita pakai PUT)
    Route::put('/songs/{song}', [SongController::class, 'update'])->name('songs.update');

    // Rute untuk MENGHAPUS lagu
    Route::delete('/songs/{song}', [SongController::class, 'destroy'])->name('songs.destroy');

    // RUTE BARU UNTUK LIKE/UNLIKE
    Route::post('/song/{song}/like', [SongLikeController::class, 'store'])->name('songs.like');
    Route::delete('/song/{song}/unlike', [SongLikeController::class, 'destroy'])->name('songs.unlike');

    Route::get('/liked-songs', [SongLikeController::class, 'index'])->name('liked.songs');
});

require __DIR__.'/auth.php';
