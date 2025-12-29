<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musik Player</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #121212; }
        ::-webkit-scrollbar-thumb { background: #555; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #888; }
        main { padding-bottom: 100px; }
        
        .song-item.playing {
            border: 1px solid #1DB954;
            background-color: #282828;
        }
        .song-item.playing .song-title { color: #1DB954; }

        /* Animasi untuk indikator repeat one */
        .repeat-one-badge {
            font-size: 8px;
            position: absolute;
            top: 3px;
            right: -4px;
            background: #1DB954;
            color: black;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-300 bg-black h-screen flex overflow-hidden">

    <!-- SIDEBAR (KIRI) -->
    <aside class="w-64 bg-black flex flex-col h-full flex-shrink-0 border-r border-neutral-800">
        <div class="p-6">
            <div class="flex items-center gap-2 text-white mb-8">
                <span class="font-bold text-xl tracking-tight">Musik Player</span>
            </div>
            
            <nav class="space-y-4">
                <a href="{{ route('home') }}" class="flex items-center gap-4 text-sm font-bold text-white hover:text-green-500 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Home
                </a>
                
                <a href="#" class="flex items-center gap-4 text-sm font-bold text-gray-400 hover:text-white transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Cari
                </a>

                @auth
                    <div class="pt-4 mt-4 border-t border-neutral-800">
                        <a href="{{ route('liked.songs') }}" class="flex items-center gap-4 text-sm font-bold text-gray-400 hover:text-white transition duration-200 group">
                            <div class="w-6 h-6 bg-gradient-to-br from-indigo-700 to-blue-300 rounded-sm flex items-center justify-center opacity-70 group-hover:opacity-100">
                                <svg class="w-3 h-3 text-white fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" /></svg>
                            </div>
                            Lagu Disukai
                        </a>
                    </div>
                @endauth
            </nav>
        </div>
    </aside>

    <!-- KONTEN UTAMA (KANAN) -->
    <main class="flex-1 bg-[#121212] overflow-y-auto h-full relative">
        
        <!-- Top Bar -->
        <div class="sticky top-0 z-20 bg-black/60 backdrop-blur-md px-6 py-4 flex justify-between items-center">
            <div class="flex-1 max-w-md">
                <form action="{{ route('home') }}" method="GET" class="relative flex items-center text-gray-400 focus-within:text-white">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" 
                           class="w-full pl-10 pr-4 py-2 rounded-full bg-[#242424] border-transparent text-white placeholder-gray-400 focus:border-white focus:ring-0 transition-colors" 
                           placeholder="Apa yang ingin kamu dengarkan?"
                           value="{{ $searchTerm ?? '' }}">
                </form>
            </div>

            <!-- Bagian Kanan: User Profile atau Login/Daftar -->
            <div class="flex items-center gap-4 ml-6">
                @auth
                    <!-- Profil User (Avatar & Nama) -->
                    <div class="flex items-center gap-2 bg-black/40 hover:bg-[#282828] rounded-full p-1 pr-3 transition cursor-pointer border border-transparent hover:border-[#3E3E3E]">
                        <div class="w-7 h-7 rounded-full bg-[#535353] flex items-center justify-center text-white font-bold text-xs shadow-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="text-sm font-bold text-white max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                    </div>

                    <!-- Link Dashboard (Ikon Setting/Admin) -->
                    <a href="{{ url('/dashboard') }}" class="text-neutral-400 hover:text-white transition" title="Admin Dashboard">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </a>

                    <!-- Tombol Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-neutral-400 hover:text-white transition pt-1" title="Log Out">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                @else
                    <!-- Tombol Tamu -->
                    <div class="flex items-center gap-6">
                        <a href="{{ route('register') }}" class="text-neutral-400 hover:text-white font-bold text-sm transition transform hover:scale-105">Daftar</a>
                        <a href="{{ route('login') }}" class="bg-white text-black font-bold py-3 px-8 rounded-full text-sm hover:scale-105 transition">Masuk</a>
                    </div>
                @endauth
            </div>
        </div>

        <div class="p-6 pt-4">
            <div class="mb-6">
                @if($searchTerm)
                    <h2 class="text-2xl font-bold text-white">Hasil Pencarian: "{{ $searchTerm }}"</h2>
                    <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white hover:underline mt-1 inline-block">Hapus pencarian</a>
                @else
                    <h2 class="text-2xl font-bold text-white">Dengarkan Lagu</h2>
                @endif
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                @forelse ($songs as $index => $song) 
                    <div class="song-item group bg-[#181818] hover:bg-[#282828] rounded-md p-4 transition duration-300 ease-in-out cursor-pointer relative flex flex-col"
                         data-index="{{ $index }}"
                         data-src="{{ Storage::url($song->file_path) }}"
                         data-title="{{ $song->title }}"
                         data-artist="{{ $song->artist }}"
                         data-cover="{{ $song->cover_art_path ? Storage::url($song->cover_art_path) : 'https://placehold.co/300x300/333/888?text=Music' }}">
                        
                        <div class="relative mb-4 aspect-square w-full shadow-lg rounded-md overflow-hidden">
                            <img src="{{ $song->cover_art_path ? Storage::url($song->cover_art_path) : 'https://placehold.co/300x300/333/888?text=Music' }}" 
                                 alt="{{ $song->title }}" 
                                 class="w-full h-full object-cover">
                            
                            <div class="absolute bottom-2 right-2 opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out z-10">
                                <div class="bg-[#1DB954] rounded-full p-3 shadow-xl hover:scale-105 hover:bg-[#1ed760] flex items-center justify-center text-black">
                                    <svg class="w-6 h-6 fill-current pl-1" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="min-h-[4rem]">
                            <h3 class="text-white font-bold truncate text-base song-title mb-1">{{ $song->title }}</h3>
                            <div class="flex flex-col gap-0.5">
                                <a href="{{ route('artist.show', ['artistName' => $song->artist]) }}" class="text-sm text-gray-400 hover:text-white hover:underline truncate song-artist relative z-20 w-fit">{{ $song->artist }}</a>
                                @if ($song->album)
                                    <a href="{{ route('album.show', ['albumName' => $song->album]) }}" class="text-xs text-gray-500 hover:text-white hover:underline truncate relative z-20 w-fit">{{ $song->album }}</a>
                                @endif
                            </div>
                        </div>

                        <div class="absolute top-4 right-4 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            @auth
                                @if (in_array($song->id, $likedSongIds))
                                    <form action="{{ route('songs.unlike', $song->id) }}" method="POST" class="inline-block m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-[#1DB954] hover:scale-110 transition p-1 bg-black/40 rounded-full backdrop-blur-sm shadow-md">
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('songs.like', $song->id) }}" method="POST" class="inline-block m-0">
                                        @csrf
                                        <button type="submit" class="text-gray-300 hover:text-white hover:scale-110 transition p-1 bg-black/40 rounded-full backdrop-blur-sm shadow-md">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.5l1.318-1.182a4.5 4.5 0 116.364 6.364L12 20.06l-7.682-7.378a4.5 4.5 0 010-6.364z"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                        <div class="bg-neutral-800 p-6 rounded-full mb-4">
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Tidak ada lagu ditemukan</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- PLAYER BAR (BAWAH) -->
    <footer id="player-container" class="fixed bottom-0 left-0 right-0 bg-[#181818] border-t border-[#282828] text-white px-4 py-3 h-[90px] flex items-center justify-between z-50">
        
        <!-- Info Lagu -->
        <div class="flex items-center w-1/3 min-w-[180px]">
            <img id="player-cover" src="https://placehold.co/56x56/333/888?text=Music" alt="Cover" class="w-14 h-14 rounded shadow-md mr-4 object-cover bg-neutral-800">
            <div class="truncate pr-4">
                <div id="player-title" class="font-sm font-bold hover:underline cursor-pointer text-white truncate">Pilih Lagu</div>
                <div id="player-artist" class="text-xs text-gray-400 hover:underline cursor-pointer truncate hover:text-white transition">...</div>
            </div>
        </div>

        <!-- Kontrol Player -->
        <div class="flex flex-col items-center w-1/3 max-w-[40%]">
            <!-- Tombol Playback -->
            <div class="flex items-center gap-6 mb-2">
                
                <!-- 1. Tombol Shuffle (BARU) -->
                <button id="shuffle-button" class="text-gray-400 hover:text-white transition transform active:scale-95 relative" title="Acak">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16"><path d="M0 3.5A.5.5 0 0 1 .5 3H1c2.202 0 3.827 1.24 4.874 2.418.49.552.865 1.102 1.126 1.532.26-.43.636-.98 1.126-1.532C9.173 4.24 10.798 3 13 3v1c-1.798 0-3.173 1.01-4.126 2.082A9.624 9.624 0 0 0 7.556 8a9.624 9.624 0 0 0 1.317 1.918C9.828 10.99 11.204 12 13 12v1c-2.202 0-3.827-1.24-4.874-2.418A10.595 10.595 0 0 1 7 9.05c-.26.43-.636.98-1.126 1.532C4.827 11.76 3.202 13 1 13H.5a.5.5 0 0 1 0-1H1c1.798 0 3.173-1.01 4.126-2.082A9.624 9.624 0 0 0 6.444 8a9.624 9.624 0 0 0-1.317-1.918C4.172 5.01 2.796 4 1 4H.5a.5.5 0 0 1-.5-.5z"/><path d="M13 5.466V1.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384l-2.36 1.966a.25.25 0 0 1-.41-.192zm0 9v-3.932a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384l-2.36 1.966a.25.25 0 0 1-.41-.192z"/></svg>
                    <!-- Titik indikator aktif -->
                    <div id="shuffle-indicator" class="hidden w-1 h-1 bg-[#1DB954] rounded-full absolute -bottom-2 left-1/2 transform -translate-x-1/2"></div>
                </button>

                <button id="prev-button" class="text-gray-400 hover:text-white transition transform active:scale-95" title="Previous">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 16 16"><path d="M3.3 8l8.7-5v10z"/><path d="M3.3 8l8.7-5v10z"/></svg>
                </button>
                
                <!-- Tombol Play/Pause -->
                <button id="play-pause-button" class="bg-white rounded-full p-2 hover:scale-105 transition transform active:scale-95 text-black">
                    <svg id="icon-play" class="w-6 h-6 fill-current pl-1" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    <svg id="icon-pause" class="w-6 h-6 fill-current hidden" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                </button>

                <button id="next-button" class="text-gray-400 hover:text-white transition transform active:scale-95" title="Next">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 16 16"><path d="M12.7 8l-8.7-5v10z"/><path d="M12.7 8l-8.7-5v10z"/></svg>
                </button>

                <!-- 2. Tombol Repeat (BARU) -->
                <button id="repeat-button" class="text-gray-400 hover:text-white transition transform active:scale-95 relative" title="Ulang">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 16 16"><path d="M11 5.466V4H5a4 4 0 0 0-3.584 5.777.5.5 0 1 1-.896.446A5 5 0 0 1 5 3h6V1.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384l-2.36 1.966a.25.25 0 0 1-.41-.192v-1.534zm-9 5.068V12h6a4 4 0 0 0 3.584-5.777.5.5 0 1 1 .896-.446A5 5 0 0 1 11 13H5v1.466a.25.25 0 0 1-.41.192l-2.36-1.966a.25.25 0 0 1 0-.384l2.36-1.966a.25.25 0 0 1 .41.192z"/></svg>
                    <!-- Titik indikator aktif -->
                    <div id="repeat-indicator" class="hidden w-1 h-1 bg-[#1DB954] rounded-full absolute -bottom-2 left-1/2 transform -translate-x-1/2"></div>
                    <!-- Badge "1" untuk Repeat One -->
                    <div id="repeat-one-badge" class="hidden repeat-one-badge">1</div>
                </button>

            </div>

            <!-- Progress Bar -->
            <div class="w-full flex items-center gap-2 text-xs text-gray-400 font-mono">
                <span id="current-time">0:00</span>
                <input type="range" id="progress-bar" value="0" class="w-full h-1 bg-gray-600 rounded-lg appearance-none cursor-pointer accent-white hover:accent-[#1DB954]">
                <span id="duration">0:00</span>
            </div>
        </div>

        <!-- Volume -->
        <div class="w-1/3 flex justify-end items-center gap-2 pr-4">
            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.972 7.972 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.414z"/></svg>
            <input type="range" id="volume-bar" min="0" max="1" step="0.1" value="1" class="w-24 h-1 bg-gray-600 rounded-lg appearance-none cursor-pointer accent-white hover:accent-[#1DB954]">
        </div>
        <audio id="audio-player" class="hidden"></audio>
    </footer>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const audioPlayer = document.getElementById('audio-player');
            const playerTitle = document.getElementById('player-title');
            const playerArtist = document.getElementById('player-artist');
            const playerCover = document.getElementById('player-cover');
            
            const prevButton = document.getElementById('prev-button');
            const nextButton = document.getElementById('next-button');
            const playPauseButton = document.getElementById('play-pause-button');
            const iconPlay = document.getElementById('icon-play');
            const iconPause = document.getElementById('icon-pause');
            
            // --- Tombol Baru ---
            const shuffleButton = document.getElementById('shuffle-button');
            const repeatButton = document.getElementById('repeat-button');
            const shuffleIndicator = document.getElementById('shuffle-indicator');
            const repeatIndicator = document.getElementById('repeat-indicator');
            const repeatOneBadge = document.getElementById('repeat-one-badge');
            // -------------------

            const progressBar = document.getElementById('progress-bar');
            const volumeBar = document.getElementById('volume-bar');
            const currentTimeEl = document.getElementById('current-time');
            const durationEl = document.getElementById('duration');

            const songItems = document.querySelectorAll('.song-item');
            
            let songs = []; 
            let currentSongIndex = -1; 
            let isPlaying = false;
            
            // --- State Baru ---
            let isShuffle = false;
            let repeatState = 0; // 0: Off, 1: All, 2: One
            // ------------------

            // Inisialisasi Lagu
            songItems.forEach((item, index) => {
                songs.push({
                    index: index,
                    src: item.dataset.src,
                    title: item.dataset.title,
                    artist: item.dataset.artist,
                    cover: item.dataset.cover,
                    element: item 
                });
            });

            // --- Logika Shuffle Button ---
            shuffleButton.addEventListener('click', () => {
                isShuffle = !isShuffle;
                if (isShuffle) {
                    shuffleButton.classList.remove('text-gray-400');
                    shuffleButton.classList.add('text-[#1DB954]');
                    shuffleIndicator.classList.remove('hidden');
                } else {
                    shuffleButton.classList.add('text-gray-400');
                    shuffleButton.classList.remove('text-[#1DB954]');
                    shuffleIndicator.classList.add('hidden');
                }
            });

            // --- Logika Repeat Button ---
            repeatButton.addEventListener('click', () => {
                repeatState = (repeatState + 1) % 3; // Cycle 0 -> 1 -> 2 -> 0
                
                // Reset styles
                repeatButton.classList.add('text-gray-400');
                repeatButton.classList.remove('text-[#1DB954]');
                repeatIndicator.classList.add('hidden');
                repeatOneBadge.classList.add('hidden');

                if (repeatState === 1) { // Repeat All
                    repeatButton.classList.remove('text-gray-400');
                    repeatButton.classList.add('text-[#1DB954]');
                    repeatIndicator.classList.remove('hidden');
                } else if (repeatState === 2) { // Repeat One
                    repeatButton.classList.remove('text-gray-400');
                    repeatButton.classList.add('text-[#1DB954]');
                    repeatIndicator.classList.remove('hidden');
                    repeatOneBadge.classList.remove('hidden');
                }
            });

            function playSong(index) {
                if (index < 0 || index >= songs.length) return;
                const song = songs[index];
                if (currentSongIndex !== index) {
                    audioPlayer.src = song.src;
                    currentSongIndex = index;
                    playerTitle.textContent = song.title;
                    playerArtist.textContent = song.artist;
                    playerCover.src = song.cover;
                    document.querySelectorAll('.song-item.playing').forEach(el => el.classList.remove('playing'));
                    song.element.classList.add('playing');
                }
                audioPlayer.play();
                setIsPlaying(true);
            }

            function togglePlay() {
                if (currentSongIndex === -1) { if (songs.length > 0) playSong(0); return; }
                if (audioPlayer.paused) { audioPlayer.play(); setIsPlaying(true); } else { audioPlayer.pause(); setIsPlaying(false); }
            }

            function setIsPlaying(status) {
                isPlaying = status;
                if (status) { iconPlay.classList.add('hidden'); iconPause.classList.remove('hidden'); } else { iconPlay.classList.remove('hidden'); iconPause.classList.add('hidden'); }
            }

            // --- Logika Next Song (Cerdas) ---
            function playNextSong(isAuto = false) {
                if (songs.length === 0) return;

                // 1. Jika Repeat One dan dipanggil otomatis (lagu selesai), ulangi lagu yang sama
                if (repeatState === 2 && isAuto) {
                    audioPlayer.currentTime = 0;
                    audioPlayer.play();
                    return;
                }

                // 2. Jika Shuffle aktif, pilih lagu acak
                if (isShuffle) {
                    let randomIndex;
                    do {
                        randomIndex = Math.floor(Math.random() * songs.length);
                    } while (randomIndex === currentSongIndex && songs.length > 1);
                    playSong(randomIndex);
                    return;
                }

                // 3. Normal Next
                let nextIndex = currentSongIndex + 1;

                // Jika sudah di akhir daftar
                if (nextIndex >= songs.length) {
                    if (repeatState === 1) { // Repeat All
                        nextIndex = 0; // Loop ke awal
                    } else {
                        // Stop jika tidak repeat
                        setIsPlaying(false);
                        return; 
                    }
                }
                playSong(nextIndex);
            }

            function playPrevSong() {
                // Jika durasi > 3 detik, restart lagu saat ini
                if (audioPlayer.currentTime > 3) {
                    audioPlayer.currentTime = 0;
                    return;
                }

                if (songs.length === 0) return;
                
                if (isShuffle) {
                     // Shuffle prev behavior biasanya sama dengan next random, atau history stack (kita pakai random simple)
                    let randomIndex = Math.floor(Math.random() * songs.length);
                    playSong(randomIndex);
                    return;
                }

                let prevIndex = currentSongIndex - 1;
                if (prevIndex < 0) prevIndex = songs.length - 1;
                playSong(prevIndex);
            }

            function formatTime(seconds) {
                const min = Math.floor(seconds / 60);
                const sec = Math.floor(seconds % 60);
                return `${min}:${sec < 10 ? '0' + sec : sec}`;
            }

            audioPlayer.addEventListener('timeupdate', () => {
                const progress = (audioPlayer.currentTime / audioPlayer.duration) * 100;
                progressBar.value = progress || 0;
                currentTimeEl.textContent = formatTime(audioPlayer.currentTime || 0);
                durationEl.textContent = formatTime(audioPlayer.duration || 0);
            });

            progressBar.addEventListener('input', () => {
                const seekTime = (progressBar.value / 100) * audioPlayer.duration;
                audioPlayer.currentTime = seekTime;
            });

            volumeBar.addEventListener('input', (e) => { audioPlayer.volume = e.target.value; });

            playPauseButton.addEventListener('click', togglePlay);
            
            // Update event listener next button untuk manual click (bukan auto)
            nextButton.addEventListener('click', () => playNextSong(false));
            
            prevButton.addEventListener('click', playPrevSong);
            
            // Update event listener ended untuk auto play
            audioPlayer.addEventListener('ended', () => playNextSong(true));

            songItems.forEach(item => {
                item.addEventListener('click', function(event) {
                    if (event.target.closest('form') || event.target.closest('button') || event.target.closest('a')) return;
                    playSong(parseInt(this.dataset.index, 10));
                });
            });
        });
    </script>
</body>
</html>