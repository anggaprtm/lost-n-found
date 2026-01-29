@php
    $hideNavigation = true;
@endphp

@extends('layouts.app')

@section('title', 'Selamat Datang di FTMM Lost & Found')

@section('content')
<div>
    {{-- ========================================================== --}}
    {{-- == SECTION 1: HERO - LOST & FOUND                        == --}}
    {{-- ========================================================== --}}
    <section
        class="min-h-screen bg-cover bg-center bg-fixed relative
               flex items-center justify-center text-white"
        style="background-image: url('/images/GKB.jpg');"
    >
        <!-- Overlay -->
        <div class="absolute inset-0
                    bg-gradient-to-b
                    from-[#073763]/90
                    to-[#741B47]/70">
        </div>

        <!-- Content -->
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1
                class="text-4xl md:text-6xl
                       font-black tracking-tight
                       text-white drop-shadow-lg"
            >
                Barang
                <span id="typing-effect" class="text-yellow-300">
                    Hilang & Ditemukan
                </span>
            </h1>

            <p class="mt-6 text-lg md:text-xl
                      text-gray-200 max-w-2xl mx-auto">
                Platform terpusat untuk melaporkan barang yang hilang
                dan menemukan kembali barang temuan di lingkungan FTMM.
            </p>

            <!-- CTA -->
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">

                <!-- Browse Lost & Found -->
                <a
                    href="{{ route('temuan.index') }}"
                    class="inline-flex items-center justify-center gap-2
                           bg-white text-[#073763]
                           rounded-full py-3 px-8
                           text-lg font-bold
                           shadow-lg
                           hover:bg-[#073763]
                           hover:text-white
                           transition-all duration-300"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0
                                 7 7 0 0114 0z" />
                    </svg>
                    Lihat Barang Hilang & Temuan
                </a>

                <!-- Report Lost -->
                <a
                    href="{{ auth()->check() && auth()->user()->role === 'admin'
                            ? route('reports.index')
                            : route('login') }}"
                    class="inline-flex items-center justify-center gap-2
                           rounded-full py-3 px-8
                           text-lg font-semibold
                           border-2 border-white/50
                           text-white
                           hover:bg-white
                           hover:text-[#073763]
                           transition-all duration-300"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 4v16m8-8H4" />
                    </svg>
                    Laporkan Kehilangan
                </a>

            </div>
        </div>
    </section>
</div>


{{-- SCRIPT & STYLE MANDIRI (TIDAK PERLU EDIT FILE LAIN) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const words = ["Dompet?", "Kunci?",  "Laptop?", "Apa Saja?", "Tumbler?"];
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