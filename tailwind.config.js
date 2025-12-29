import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // --- TAMBAHAN WARNA ---
            colors: {
                spotify: {
                    black: '#121212',       // Background utama
                    dark: '#181818',        // Background kartu/sidebar
                    light: '#282828',       // Background hover
                    primary: '#1DB954',     // Hijau Spotify
                    white: '#FFFFFF',       // Teks utama
                    gray: '#B3B3B3',        // Teks sekunder
                }
            }
            // ----------------------
        },
    },

    plugins: [forms],
};