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
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'tedja-black':     '#060922',
                'tedja-blue':      '#3F52FF',
                'tedja-green':     '#CEF27F',
                'tedja-orange':    '#FF9F47',
                'tedja-red':       '#FF3E3E',
                'tedja-border':    '#F2F2F4',
                'tedja-secondary': '#8F91A2',
            },
        },
    },

    plugins: [forms],
};
