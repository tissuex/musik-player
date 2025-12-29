<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Menampilkan homepage dengan daftar semua lagu (atau hasil pencarian).
     */
    public function index(Request $request)
    {
        $likedSongIds = [];
        if (Auth::check()) {
            $likedSongIds = Auth::user()->likedSongs()->pluck('songs.id')->toArray();
        }

        $searchTerm = $request->input('search');
        $query = Song::query();

        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('artist', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('album', 'LIKE', "%{$searchTerm}%");
            });
        }

        $songs = $query->orderBy('artist')->orderBy('title')->get();
        
        return view('welcome', [
            'songs' => $songs,
            'searchTerm' => $searchTerm,
            'likedSongIds' => $likedSongIds
        ]);
    }

    /**
     * Menampilkan halaman detail untuk satu album.
     */
    public function showAlbum($albumName)
    {
        $likedSongIds = [];
        if (Auth::check()) {
            $likedSongIds = Auth::user()->likedSongs()->pluck('songs.id')->toArray();
        }

        $songs = Song::where('album', $albumName)
                     ->orderBy('title')
                     ->get();

        if ($songs->isEmpty()) {
            abort(404);
        }

        $firstSong = $songs->first();
        $artist = $firstSong->artist;
        $coverArt = $firstSong->cover_art_path;

        return view('album', [
            'albumName' => $albumName,
            'artist' => $artist,
            'coverArt' => $coverArt,
            'songs' => $songs,
            'likedSongIds' => $likedSongIds,
            'searchTerm' => null // <-- PERBAIKAN: Tambahkan ini agar tidak error
        ]);
    }

    /**
     * Menampilkan halaman detail untuk satu artis.
     */
    public function showArtist($artistName)
    {
        $likedSongIds = [];
        if (Auth::check()) {
            $likedSongIds = Auth::user()->likedSongs()->pluck('songs.id')->toArray();
        }

        $songs = Song::where('artist', $artistName)
                     ->orderBy('album')
                     ->orderBy('title')
                     ->get();

        if ($songs->isEmpty()) {
            abort(404);
        }

        return view('artist', [
            'artistName' => $artistName,
            'songs' => $songs,
            'likedSongIds' => $likedSongIds,
            'searchTerm' => null // <-- PERBAIKAN: Tambahkan ini agar tidak error
        ]);
    }
}