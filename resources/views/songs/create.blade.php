<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Lagu Baru') }}
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

                    <form action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Lagu</label>
                            <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mb-4">
                            <label for="artist" class="block text-sm font-medium text-gray-700">Artis</label>
                            <input type="text" name="artist" id="artist" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mb-4">
                            <label for="album" class="block text-sm font-medium text-gray-700">Album (Opsional)</label>
                            <input type="text" name="album" id="album" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label for="song_file" class="block text-sm font-medium text-gray-700">File Lagu (MP3, WAV)</label>
                            <input type="file" name="song_file" id="song_file" class="mt-1 block w-full" required>
                        </div>

                        <div class="mb-4">
                            <label for="cover_art" class="block text-sm font-medium text-gray-700">Cover Art (Opsional)</label>
                            <input type="file" name="cover_art" id="cover_art" class="mt-1 block w-full">
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-400 rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-100">
                        Upload
                            </button>
                        </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>