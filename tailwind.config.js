/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "node_modules/preline/dist/*.js",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./public/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                'signature-1': ['signature-1'],
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("preline/plugin"),
        require("daisyui"),
    ],
    purge: {
        enabled: process.env.NODE_ENV === 'production',
        content: [
            "node_modules/preline/dist/*.js",
            "./resources/**/*.blade.php",
            "./resources/**/*.js",
            "./resources/**/*.vue",
        ],
        options: {
            safelist: [], // Add any necessary safelist entries
        },
    },
    daisyui: {
        themes: false,
    },
};
