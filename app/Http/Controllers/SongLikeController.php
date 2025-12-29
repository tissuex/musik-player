<?php

namespace App\Http\Controllers;

use App\Models\Song; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class SongLikeController extends Controller
{
    /**
     * Menyimpan "like" baru untuk sebuah lagu.
     */
    public function store(Request $request, Song $song)
    {
        $user = Auth::user(); // Dapatkan user yang sedang login

        // Gunakan attach() untuk menambahkan relasi di tabel pivot
        // 'false' mencegah duplikat jika (karena alasan aneh) sudah ada
        $user->likedSongs()->attach($song->id, [], false); 

        // Kembali ke halaman sebelumnya
        return back();
    }

    /**
     * Menghapus "like" dari sebuah lagu.
     */
    public function destroy(Request $request, Song $song)
    {
        $user = Auth::user(); // Dapatkan user yang sedang login

        // Gunakan detach() untuk menghapus relasi
        $user->likedSongs()->detach($song->id);

        // Kembali ke halaman sebelumnya
        return back();
    }

    public function index()
    {
        // 1. Dapatkan pengguna yang sedang login
        $user = Auth::user();
    
        // 2. Ambil semua lagu yang dia sukai (menggunakan relasi yg kita buat)
        // Kita juga urutkan berdasarkan artis
        $likedSongs = $user->likedSongs()->orderBy('artist')->get();
    
        // 3. Kita juga perlu ID-nya, agar tombol like/unlike di halaman
        //    ini bisa berfungsi dengan benar
        $likedSongIds = $likedSongs->pluck('id')->toArray();
    
        // 4. Kirim semua data ke view 'liked-songs' (yang akan kita buat)
        return view('liked-songs', [
            'songs' => $likedSongs,
            'likedSongIds' => $likedSongIds
        ]);
    }
}