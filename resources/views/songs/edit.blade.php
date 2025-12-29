<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- PERUBAHAN 1: Judul diubah --}}
            {{ __('Edit Lagu') }}: {{ $song->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- PERUBAHAN 2: Form action diubah ke 'songs.update' --}}
                    <form action="{{ route('songs.update', $song->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- PERUBAHAN 3: Menambahkan method PUT --}}
                        
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Lagu</label>
                            {{-- PERUBAHAN 4: Menambahkan 'value' --}}
                            <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                   value="{{ old('title', $song->title) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="artist" class="block text-sm font-medium text-gray-700">Artis</label>
                            {{-- PERUBAHAN 5: Menambahkan 'value' --}}
                            <input type="text" name="artist" id="artist" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                   value="{{ old('artist', $song->artist) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="album" class="block text-sm font-medium text-gray-700">Album (Opsional)</label>
                            {{-- PERUBAHAN 6: Menambahkan 'value' --}}
                            <input type="text" name="album" id="album" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                   value="{{ old('album', $song->album) }}">
                        </div>

                        <div class="mb-4">
                            {{-- PERUBAHAN 7: Label & field file diubah --}}
                            <label for="song_file" class="block text-sm font-medium text-gray-700">Ganti File Lagu (Opsional)</label>
                            <input type="file" name="song_file" id="song_file" class="mt-1 block w-full" >
                            <span class="text-xs text-gray-500">File sekarang: {{ $song->file_path }}</span>
                        </div>

                        <div class="mb-4">
                            {{-- PERUBAHAN 8: Label & field file diubah + preview --}}
                            <label for="cover_art" class="block text-sm font-medium text-gray-700">Ganti Cover Art (Opsional)</label>
                            <input type="file" name="cover_art" id="cover_art" class="mt-1 block w-full">
                            @if($song->cover_art_path)
                            <img src="{{ Storage::url($song->cover_art_path) }}" alt="Cover" class="w-20 h-20 mt-2 rounded">
                            @endif
                        </div>

                        <div class="flex space-x-4">
                            {{-- PERUBAHAN 9: Teks tombol diubah, style Anda dipertahankan --}}
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-400 rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-100">
                                Update
                            </button>
                        </div>
                </div>
                </form>

            </div>
        </div>
    </div>
    </div>
</x-app-layout>