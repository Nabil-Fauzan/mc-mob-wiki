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
            colors: {
                brand: {
                    50: 'var(--mc-brand-50, #f0f9ff)',
                    100: 'var(--mc-brand-100, #e0f2fe)',
                    200: 'var(--mc-brand-200, #bae6fd)',
                    300: 'var(--mc-brand-300, #7dd3fc)',
                    400: 'var(--mc-brand-400, #38bdf8)',
                    500: 'var(--mc-brand-500, #0ea5e9)',
                    600: 'var(--mc-brand-600, #0284c7)',
                    700: 'var(--mc-brand-700, #0369a1)',
                    800: 'var(--mc-brand-800, #075985)',
                    900: 'var(--mc-brand-900, #0c4a6e)',
                    950: 'var(--mc-brand-950, #082f49)',
                },
                accent: {
                    400: '#22d3ee',
                }
            }
        },
    },

    plugins: [
        forms,
        require('@tailwindcss/typography'),
    ],
};
