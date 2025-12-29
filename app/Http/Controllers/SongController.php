<?php

namespace App\Http\Controllers;

// TAMBAHKAN 'use' statement INI:
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Penting untuk mengelola file

class SongController extends Controller
{
    /**
     * Menampilkan halaman dashboard (nanti akan kita isi daftar lagu).
     */
    public function index()
    {
        // Ambil semua lagu dari database
        $songs = Song::all(); 
        
        // Kirim data lagu ke view 'dashboard'
        // Kita akan edit view 'dashboard' nanti untuk menampilkannya
        return view('dashboard', ['songs' => $songs]);
    }

    /**
     * Menampilkan halaman/formulir upload lagu.
     */
    public function create()
    {
        // Ini hanya akan menampilkan file view yang sudah kita buat
        return view('songs.create');
    }

    /**
     * Menyimpan lagu baru dari formulir upload.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'album' => 'nullable|string|max:255',
            'song_file' => 'required|file|mimes:mp3,wav,m4a', // Hanya izinkan file audio
            'cover_art' => 'nullable|image|mimes:jpg,png,jpeg',
        ]);

        // 2. Simpan file lagu
        // File akan disimpan di 'storage/app/public/songs'
        $songPath = $request->file('song_file')->store('songs', 'public');
        
        $coverPath = null;
        if ($request->hasFile('cover_art')) {
            // 3. Simpan file cover jika ada
            // File akan disimpan di 'storage/app/public/covers'
            $coverPath = $request->file('cover_art')->store('covers', 'public');
        }

        // 4. Simpan info lagu ke database
        Song::create([
            'title' => $request->title,
            'artist' => $request->artist,
            'album' => $request->album,
            'file_path' => $songPath,
            'cover_art_path' => $coverPath,
        ]);

        // 5. Kembali ke dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Lagu berhasil di-upload!');
    }

    // --- INI FUNGSI BARU YANG SAYA TAMBAHKAN ---

    /**
     * Menampilkan formulir untuk mengedit lagu.
     * $song otomatis diambil dari database berkat Route Model Binding.
     */
    public function edit(Song $song)
    {
        return view('songs.edit', [
            'song' => $song
        ]);
    }

    /**
     * Memproses update data lagu.
     */
    public function update(Request $request, Song $song)
    {
        // 1. Validasi (mirip store, tapi file tidak 'required')
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'album' => 'nullable|string|max:255',
            'song_file' => 'nullable|file|mimes:mp3,wav,m4a', // Tidak wajib
            'cover_art' => 'nullable|image|mimes:jpg,png,jpeg', // Tidak wajib
        ]);

        // 2. Cek jika ada file lagu BARU di-upload
        if ($request->hasFile('song_file')) {
            // Hapus file lama (jika ada)
            if ($song->file_path) {
                Storage::disk('public')->delete($song->file_path);
            }
            // Simpan file baru
            $data['file_path'] = $request->file('song_file')->store('songs', 'public');
        }

        // 3. Cek jika ada file cover BARU di-upload
        if ($request->hasFile('cover_art')) {
            // Hapus cover lama (jika ada)
            if ($song->cover_art_path) {
                Storage::disk('public')->delete($song->cover_art_path);
            }
            // Simpan cover baru
            $data['cover_art_path'] = $request->file('cover_art')->store('covers', 'public');
        }

        // 4. Update data lagu di database
        $song->update($data);

        // 5. Kembali ke dashboard
        return redirect()->route('dashboard')->with('success', 'Lagu berhasil di-update!');
    }
    /**
     * Menghapus lagu beserta file terkait.
     */
    public function destroy(Song $song)
    {
    // 1. Hapus file lagu dari storage
    if ($song->file_path) {
        Storage::disk('public')->delete($song->file_path);
    }

    // 2. Hapus file cover art dari storage
    if ($song->cover_art_path) {
        Storage::disk('public')->delete($song->cover_art_path);
    }

    // 3. Hapus data lagu dari database
    $song->delete();

    // 4. Kembali ke dashboard dengan pesan sukses
    return redirect()->route('dashboard')->with('success', 'Lagu berhasil dihapus!');
    }
}