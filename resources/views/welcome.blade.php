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
    {{-- == SECTION 3: DAFTAR LAPORAN (SUDAH ADA)                == --}}
    {{-- ========================================================== --}}
    @include('reports._public_reports_list')

</div>

{{-- SCRIPT & STYLE MANDIRI (TIDAK PERLU EDIT FILE LAIN) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const words = ["Dompet?", "Kunci?", "KTS?", "Laptop?", "Apa Saja?", "TumblrTuku?"];
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