/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.php", "!./node_modules/**"],
  safelist: [
    "hidden",
    "bg-white",
    "bg-white/45",
    "custom-logo-link",
    "custom-logo",
  ],
  theme: {
    extend: {
      colors: {
        "gy-blue-900": "#0b2f64",
        "gy-blue-700": "#1456a0",
        "gy-blue-500": "#2e7ed8",
        "gy-blue-100": "#e8f1fc",
        "gy-ink": "#16324f",
        "gy-warm": "#f7fbff",
      },
      fontFamily: {
        sans: ["Poppins", "Segoe UI", "sans-serif"],
      },
    },
  },
  plugins: [require("@tailwindcss/typography")],
};
