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
                'tedja-blue':      '#111111', /* was: #3F52FF */
                'tedja-green':     '#111111', /* was: #CEF27F */
                'tedja-orange':    '#888888', /* was: #FF9F47 */
                'tedja-red':       '#555555', /* was: #FF3E3E */
                'tedja-border':    '#F2F2F4',
                'tedja-secondary': '#8F91A2',
            },
        },
    },

    plugins: [forms],
};
