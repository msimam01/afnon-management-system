import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // or 'media' or 'class'
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'sans-serif', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#2563eb',
                    dark: '#1d4ed8',
                },
                success: {
                    DEFAULT: '#059669',
                    dark: '#047857',
                },
                danger: {
                    DEFAULT: '#dc2626',
                    dark: '#b91c1c',
                },
                warning: {
                    DEFAULT: '#d97706',
                    dark: '#b45309',
                },
            },
            boxShadow: {
                'lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
            },
        },
    },

    plugins: [
        forms,
        require('flowbite/plugin')({
            charts: true,
        }),
    ],
};
