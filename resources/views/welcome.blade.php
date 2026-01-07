@php
    $hideNavigation = true;
@endphp

@extends('layouts.app')

@section('title', 'Selamat Datang di FTMM Lost & Found')

@section('content')
<div>
    {{-- ========================================================== --}}
    {{-- == SECTION 1: HERO - DENGAN EFEK TULISAN MENGETIK        == --}}
    {{-- ========================================================== --}}
    <section class="min-h-screen bg-cover bg-center bg-fixed relative flex items-center justify-center text-white" style="background-image: url('/images/GKB.jpg');">
        <div class="absolute inset-0 bg-gradient-to-b from-[#073763]/90 to-[#741B47]/70"></div>
        
        <div class="z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-black tracking-tight text-white drop-shadow-lg">
                Kehilangan <span id="typing-effect" class="text-yellow-300"></span>
            </h1>
            <p class="mt-6 text-lg md:text-xl text-gray-200 max-w-2xl mx-auto">
                Platform terpusat untuk melaporkan dan menemukan kembali barang berharga Anda di lingkungan FTMM.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#temuan" class="btn-primary bg-white text-[#073763] rounded-full py-3 px-8 text-lg font-bold transform hover:scale-105 transition-transform">
                    Lihat Barang Temuan
                </a>
                <a href="{{ route('login') }}" class="btn-outline border-2 border-white/50 text-white rounded-full py-3 px-8 text-lg font-semibold hover:bg-white hover:text-[#073763] transition-colors">
                    Lapor Kehilangan
                </a>
            </div>
        </div>
        
        {{-- Panah Scroll Down (Sudah Diperbaiki) --}}
        <div class="absolute bottom-10 z-10">
            <a href="#fitur" aria-label="Scroll ke bagian fitur" class="p-2 animate-bounce">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7"></path>
                </svg>
            </a>
        </div>
    </section>

    {{-- ========================================================== --}}
    {{-- == SECTION 2: FITUR UNGGULAN - DENGAN IKON & HOVER EFEK == --}}
    {{-- ========================================================== --}}
    <section id="fitur" class="bg-white py-20 sm:py-28">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Alur Kerja yang Sederhana</h2>
                <p class="text-lg text-gray-600 mt-4">Tiga langkah mudah untuk ketenangan Anda.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="text-center p-6 group">
                    <div class="flex items-center justify-center h-20 w-20 rounded-full bg-gray-100 group-hover:bg-[#073763] transition-colors duration-300 mx-auto">
                        <svg class="w-10 h-10 text-[#073763] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-800">1. Buat Laporan</h3>
                    <p class="mt-2 text-gray-600">Laporkan barang hilang atau temuan Anda dengan detail lengkap dalam hitungan menit.</p>
                </div>
                <div class="text-center p-6 group">
                    <div class="flex items-center justify-center h-20 w-20 rounded-full bg-gray-100 group-hover:bg-[#741B47] transition-colors duration-300 mx-auto">
                         <svg class="w-10 h-10 text-[#741B47] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-800">2. Proses Verifikasi</h3>
                    <p class="mt-2 text-gray-600">Petugas kami akan memverifikasi setiap laporan untuk memastikan keakuratannya.</p>
                </div>
                <div class="text-center p-6 group">
                    <div class="flex items-center justify-center h-20 w-20 rounded-full bg-gray-100 group-hover:bg-[#C0C0C0] transition-colors duration-300 mx-auto">
                        <svg class="w-10 h-10 text-[#6b7280] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-800">3. Klaim & Ambil</h3>
                    <p class="mt-2 text-gray-600">Ajukan klaim, dan setelah disetujui, ambil barang Anda di Kantor Kemahasiswaan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================================== --}}
    {{-- == SECTION 3: FAQ - KEMBALI DENGAN 6 PERTANYAAN         == --}}
    {{-- ========================================================== --}}
    <section class="bg-[#073763] text-white py-20 sm:py-28">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Pertanyaan Umum</h2>
            <div class="space-y-4">
                <details class="group bg-white/10 rounded-xl p-6" open>
                    <summary class="flex justify-between items-center cursor-pointer list-none">
                        <h3 class="text-lg font-semibold">Bagaimana cara membuat laporan?</h3>
                        <div class="group-open:rotate-180 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7 7"></path></svg></div>
                    </summary>
                    <p class="mt-4 text-gray-300">Login, pilih menu “Laporan Saya”, klik "Buat Laporan", lalu isi detail dan foto.</p>
                </details>
                <details class="group bg-white/10 rounded-xl p-6">
                    <summary class="flex justify-between items-center cursor-pointer list-none">
                        <h3 class="text-lg font-semibold">Bagaimana cara mengklaim barang?</h3>
                        <div class="group-open:rotate-180 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7 7"></path></svg></div>
                    </summary>
                    <p class="mt-4 text-gray-300">Pada detail barang temuan, klik “Klaim Barang” dan isi bukti kepemilikan.</p>
                </details>
                <details class="group bg-white/10 rounded-xl p-6">
                    <summary class="flex justify-between items-center cursor-pointer list-none">
                        <h3 class="text-lg font-semibold">Siapa yang memverifikasi?</h3>
                        <div class="group-open:rotate-180 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7 7"></path></svg></div>
                    </summary>
                    <p class="mt-4 text-gray-300">Petugas Fakultas akan memvalidasi setiap laporan & klaim agar terpercaya.</p>
                </details>
                <details class="group bg-white/10 rounded-xl p-6">
                    <summary class="flex justify-between items-center cursor-pointer list-none">
                        <h3 class="text-lg font-semibold">Berapa lama proses verifikasi?</h3>
                        <div class="group-open:rotate-180 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7 7"></path></svg></div>
                    </summary>
                    <p class="mt-4 text-gray-300">Biasanya dalam 1–3 hari kerja, tergantung volume laporan yang masuk.</p>
                </details>
                <details class="group bg-white/10 rounded-xl p-6">
                    <summary class="flex justify-between items-center cursor-pointer list-none">
                        <h3 class="text-lg font-semibold">Apa yang harus saya bawa ketika ambil barang?</h3>
                        <div class="group-open:rotate-180 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7 7"></path></svg></div>
                    </summary>
                    <p class="mt-4 text-gray-300">Siapkan identitas (KTM/KTP) dan bukti klaim untuk verifikasi petugas.</p>
                </details>
                <details class="group bg-white/10 rounded-xl p-6">
                    <summary class="flex justify-between items-center cursor-pointer list-none">
                        <h3 class="text-lg font-semibold">Bagaimana jika terjadi konflik klaim ganda?</h3>
                        <div class="group-open:rotate-180 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7 7"></path></svg></div>
                    </summary>
                    <p class="mt-4 text-gray-300">Jika ada lebih dari satu klaim, petugas akan memverifikasi bukti dan memutuskan satu yang sah.</p>
                </details>
            </div>
        </div>
    </section>

    {{-- ========================================================== --}}
    {{-- == SECTION 4: DAFTAR LAPORAN (SUDAH ADA)                == --}}
    {{-- ========================================================== --}}
    @include('reports._public_reports_list')

</div>

{{-- SCRIPT & STYLE MANDIRI (TIDAK PERLU EDIT FILE LAIN) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const words = ["Dompet?", "Kunci?", "KTM?", "Laptop?", "Apa Saja?", "Tumblr Tuku?"];
    let i = 0;
    let j = 0;
    let currentWord = "";
    let isDeleting = false;
    const typingEffectElement = document.getElementById("typing-effect");

    function type() {
        currentWord = words[i];
        if (isDeleting) {
            typingEffectElement.textContent = currentWord.substring(0, j - 1);
            j--;
            if (j === 0) {
                isDeleting = false;
                i++;
                if (i === words.length) {
                    i = 0;
                }
            }
        } else {
            typingEffectElement.textContent = currentWord.substring(0, j + 1);
            j++;
            if (j === currentWord.length) {
                isDeleting = true;
                setTimeout(() => type(), 2000); // Jeda sebelum menghapus
                return;
            }
        }
        setTimeout(type, isDeleting ? 100 : 200);
    }
    type();
});
</script>

<style>
/* Menghilangkan panah default dari tag <details> */
details > summary {
  list-style: none;
}
details > summary::-webkit-details-marker {
  display: none;
}
</style>
@endsection