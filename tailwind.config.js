// tailwind.config.js

export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
        // ▼▼▼ TAMBAHKAN KODE INI ▼▼▼
        colors: {
            'prussian-blue': '#073763',
            'silver': '#C0C0C0',
            'pompadour': '#741B47',
            'success': '#16a34a',
            'danger': '#dc2626',
        },
        // ▲▲▲ SAMPAI SINI ▲▲▲
    },
  },
  plugins: [],
}