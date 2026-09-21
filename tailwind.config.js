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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                maroon: {
                    50: '#fdf2f4',
                    100: '#fce7eb',
                    200: '#f9d0d9',
                    300: '#f4a9b8',
                    400: '#ec7691',
                    500: '#df4a6c',
                    600: '#cc2a54',
                    700: '#ab1d44',
                    800: '#8f1c3d',
                    900: '#7b1e38',
                    950: '#440a1b',
                },
            },
        },
    },

    plugins: [forms],
};
