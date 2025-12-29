<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artis: {{ $artistName }} - Musik Player</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #121212; }
        ::-webkit-scrollbar-thumb { background: #555; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #888; }
        main { padding-bottom: 100px; }
        .song-item.playing {
            border: 2px solid #1DB954;
            background-color: #282828;
        }
        .song-item.playing .song-title { color: #1DB954; }
    </style>
</head>
<body class="font-sans antialiased text-gray-300 bg-neutral-900 h-screen flex overflow-hidden">

    <!-- SIDEBAR (SAMA SEPERTI WELCOME) -->
    <aside class="w-64 bg-black flex flex-col h-full flex-shrink-0 border-r border-neutral-800">
        <div class="p-6">
            <div class="flex items-center gap-2 text-white mb-8">
                <span class="font-bold text-xl tracking-tight">Musik Player</span>
            </div>
            
            <nav class="space-y-4">
                <a href="{{ route('home') }}" class="flex items-center gap-4 text-sm font-bold text-gray-400 hover:text-white transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Home
                </a>
                
                <!-- Link Cari (Dummy) -->
                <a href="#" class="flex items-center gap-4 text-sm font-bold text-gray-400 hover:text-white transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Cari
                </a>

                @guest
                    <div class="pt-6 mt-6 border-t border-neutral-800 flex flex-col gap-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Akses Penuh</p>
                        <a href="{{ route('register') }}" class="bg-white text-black font-bold py-2.5 px-4 rounded-full text-center text-sm hover:scale-105 transition transform">Daftar Gratis</a>
                        <a href="{{ route('login') }}" class="border border-gray-500 text-white font-bold py-2.5 px-4 rounded-full text-center text-sm hover:border-white hover:text-white transition">Masuk</a>
                    </div>
                @endguest

                @auth
                    <div class="pt-4 mt-4 border-t border-neutral-800">
                        <a href="{{ route('liked.songs') }}" class="flex items-center gap-4 text-sm font-bold text-gray-400 hover:text-white transition duration-200 group">
                            <div class="w-6 h-6 bg-gradient-to-br from-indigo-700 to-blue-300 rounded-sm flex items-center justify-center opacity-70 group-hover:opacity-100">
                                <svg class="w-3 h-3 text-white fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" /></svg>
                            </div>
                            Lagu Sukaanku
                        </a>
                    </div>
                @endauth
            </nav>
        </div>

        @auth
            <div class="mt-auto p-6 border-t border-neutral-800 bg-black">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-neutral-700 flex items-center justify-center text-white font-bold border border-neutral-600">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-gray-400 hover:text-white transition">Log Out</button>
                </form>
            </div>
        @endauth
    </aside>

    <!-- KONTEN UTAMA (KANAN) -->
    <main class="flex-1 bg-gradient-to-b from-[#1e1e1e] to-[#121212] overflow-y-auto h-full relative">
        
        <!-- Top Bar -->
        <div class="sticky top-0 z-20 bg-black/40 backdrop-blur-md px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <!-- Tombol Back -->
                <a href="{{ route('home') }}" class="bg-black/60 hover:bg-black/80 rounded-full p-2 text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <span class="text-sm font-bold text-gray-300">Halaman Artis</span>
            </div>

            @auth
                <a href="{{ url('/dashboard') }}" class="ml-4 text-xs font-bold text-neutral-300 hover:text-white bg-black/30 hover:bg-black/50 px-4 py-2 rounded-full transition border border-transparent hover:border-neutral-600">
                    Admin Area
                </a>
            @endauth
        </div>

        <div class="p-6 pt-4">
            
            <!-- Header Artis Besar -->
            <div class="flex items-end gap-6 mb-8 pb-6 border-b border-neutral-800">
                <!-- Placeholder Gambar Artis (bisa diganti dinamis jika ada foto artis) -->
                <div class="w-40 h-40 sm:w-52 sm:h-52 shadow-2xl rounded-full overflow-hidden bg-neutral-800 flex items-center justify-center">
                    <svg class="w-20 h-20 text-neutral-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <span class="text-sm font-bold uppercase tracking-wider text-white">Artis Terverifikasi</span>
                    <h1 class="text-4xl sm:text-6xl md:text-7xl font-black text-white mt-2 mb-4 tracking-tight">{{ $artistName }}</h1>
                    <p class="text-gray-400 text-sm font-medium">{{ $songs->count() }} Lagu Tersedia</p>
                </div>
            </div>

            <!-- Judul Section -->
            <div class="mb-4">
                <h2 class="text-xl font-bold text-white">Populer</h2>
            </div>

            <!-- List Lagu (Bukan Grid, tapi List Baris seperti Spotify Artist Page) -->
            <div class="flex flex-col">
                <!-- Header Tabel -->
                <div class="grid grid-cols-[auto_1fr_auto] gap-4 px-4 py-2 border-b border-neutral-800 text-gray-400 text-sm uppercase tracking-wider mb-2">
                    <div class="w-8 text-center">#</div>
                    <div>Judul</div>
                    <div class="w-8 text-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                </div>

                @forelse ($songs as $index => $song) 
                    <div class="song-item group grid grid-cols-[auto_1fr_auto] gap-4 items-center px-4 py-3 rounded-md hover:bg-[#2a2a2a] transition duration-200 cursor-pointer"
                         data-index="{{ $index }}"
                         data-src="{{ Storage::url($song->file_path) }}"
                         data-title="{{ $song->title }}"
                         data-artist="{{ $song->artist }}"
                         data-cover="{{ $song->cover_art_path ? Storage::url($song->cover_art_path) : 'https://placehold.co/300x300/333/888?text=Music' }}">
                        
                        <!-- Nomor Urut / Tombol Play -->
                        <div class="w-8 text-center text-gray-400 font-medium flex justify-center items-center">
                            <span class="group-hover:hidden">{{ $index + 1 }}</span>
                            <svg class="w-4 h-4 text-white hidden group-hover:block fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>

                        <!-- Info Lagu -->
                        <div class="flex items-center gap-4 overflow-hidden">
                            <img src="{{ $song->cover_art_path ? Storage::url($song->cover_art_path) : 'https://placehold.co/300x300/333/888?text=Music' }}" 
                                 class="w-10 h-10 rounded object-cover flex-shrink-0" alt="Cover">
                            <div class="flex flex-col truncate">
                                <span class="text-white font-medium text-base truncate song-title">{{ $song->title }}</span>
                                <!-- Link Album -->
                                @if ($song->album)
                                    <a href="{{ route('album.show', ['albumName' => $song->album]) }}" class="text-sm text-gray-400 hover:text-white hover:underline truncate relative z-10 w-fit">
                                        {{ $song->album }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Aksi & Durasi (Placeholder) -->
                        <div class="flex items-center gap-4">
                            <!-- Tombol Like -->
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                @auth
                                    @if (in_array($song->id, $likedSongIds))
                                        <form action="{{ route('songs.unlike', $song->id) }}" method="POST" class="inline-block m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-[#1DB954] hover:scale-110 transition p-1">
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('songs.like', $song->id) }}" method="POST" class="inline-block m-0">
                                            @csrf
                                            <button type="submit" class="text-gray-400 hover:text-white hover:scale-110 transition p-1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.5l1.318-1.182a4.5 4.5 0 116.364 6.364L12 20.06l-7.682-7.378a4.5 4.5 0 010-6.364z"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                            <!-- Durasi (Dummy) -->
                            <span class="text-sm text-gray-400 font-mono">3:45</span>
                        </div>

                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500">
                        Belum ada lagu.
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- PLAYER BAR (SAMA PERSIS) -->
    <footer id="player-container" class="fixed bottom-0 left-0 right-0 bg-[#181818] border-t border-[#282828] text-white px-4 py-3 h-[90px] flex items-center justify-between z-50">
        <!-- ... (Isi Footer sama persis dengan welcome.blade.php) ... -->
        <div class="flex items-center w-1/3 min-w-[180px]">
            <img id="player-cover" src="https://placehold.co/56x56/333/888?text=Music" alt="Cover" class="w-14 h-14 rounded shadow-md mr-4 object-cover bg-neutral-800">
            <div class="truncate pr-4">
                <div id="player-title" class="font-sm font-bold hover:underline cursor-pointer text-white truncate">Pilih Lagu</div>
                <div id="player-artist" class="text-xs text-gray-400 hover:underline cursor-pointer truncate hover:text-white transition">...</div>
            </div>
        </div>
        <div class="flex flex-col items-center w-1/3 max-w-[40%]">
            <div class="flex items-center gap-6 mb-2">
                <button id="prev-button" class="text-gray-400 hover:text-white transition transform active:scale-95">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 16 16"><path d="M3.3 8l8.7-5v10z"/><path d="M3.3 8l8.7-5v10z"/></svg>
                </button>
                <button id="play-pause-button" class="bg-white rounded-full p-2 hover:scale-105 transition transform active:scale-95 text-black">
                    <svg id="icon-play" class="w-6 h-6 fill-current pl-1" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    <svg id="icon-pause" class="w-6 h-6 fill-current hidden" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                </button>
                <button id="next-button" class="text-gray-400 hover:text-white transition transform active:scale-95">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 16 16"><path d="M12.7 8l-8.7-5v10z"/><path d="M12.7 8l-8.7-5v10z"/></svg>
                </button>
            </div>
            <div class="w-full flex items-center gap-2 text-xs text-gray-400 font-mono">
                <span id="current-time">0:00</span>
                <input type="range" id="progress-bar" value="0" class="w-full h-1 bg-gray-600 rounded-lg appearance-none cursor-pointer accent-white hover:accent-[#1DB954]">
                <span id="duration">0:00</span>
            </div>
        </div>
        <div class="w-1/3 flex justify-end items-center gap-2 pr-4">
            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.972 7.972 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.414z"/></svg>
            <input type="range" id="volume-bar" min="0" max="1" step="0.1" value="1" class="w-24 h-1 bg-gray-600 rounded-lg appearance-none cursor-pointer accent-white hover:accent-[#1DB954]">
        </div>
        <audio id="audio-player" class="hidden"></audio>
    </footer>

    <!-- SCRIPT (SAMA PERSIS) -->
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
            const progressBar = document.getElementById('progress-bar');
            const volumeBar = document.getElementById('volume-bar');
            const currentTimeEl = document.getElementById('current-time');
            const durationEl = document.getElementById('duration');
            const songItems = document.querySelectorAll('.song-item');
            
            let songs = []; 
            let currentSongIndex = -1; 
            let isPlaying = false;

            songItems.forEach((item, index) => {
                songs.push({ index: index, src: item.dataset.src, title: item.dataset.title, artist: item.dataset.artist, cover: item.dataset.cover, element: item });
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

            function playNextSong() {
                if (songs.length === 0) return;
                let nextIndex = currentSongIndex + 1;
                if (nextIndex >= songs.length) nextIndex = 0;
                playSong(nextIndex);
            }

            function playPrevSong() {
                if (songs.length === 0) return;
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
            nextButton.addEventListener('click', playNextSong);
            prevButton.addEventListener('click', playPrevSong);
            audioPlayer.addEventListener('ended', playNextSong);

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