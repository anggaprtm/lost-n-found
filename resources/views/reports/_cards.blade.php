
@php
function highlight($text, $word) {
    if (!$word) return e($text);

    return preg_replace(
        '/' . preg_quote($word, '/') . '/i',
        '<mark class="bg-yellow-200 px-1 rounded">$0</mark>',
        e($text)
    );
}
@endphp

 @if($reports->count() > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

    @foreach($reports as $report)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden
                border border-gray-100
                hover:shadow-xl transition-all duration-300 group">

        <a href="{{ route('reports.show', $report) }}" class="block relative">
            <div class="h-48 bg-gray-100 overflow-hidden">
                @if($report->photo)
                    <img src="{{ Storage::url($report->photo) }}"
                            class="w-full h-full object-cover
                                group-hover:scale-105 transition-transform">
                @else
                    <div class="w-full h-full flex items-center
                                justify-center bg-gray-200">
                        <svg class="w-10 h-10 text-gray-400"
                                fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16" />
                        </svg>
                    </div>
                @endif

                {{-- TYPE --}}
                <div class="absolute top-2 left-2">
                    <span class="px-2 py-0.5 rounded-full
                                    text-[10px] font-semibold uppercase
                                    {{ $report->type === 'found'
                                    ? 'bg-green-100 text-green-700 border border-green-200'
                                    : 'bg-red-100 text-red-700 border border-red-200' }}">
                        {{ $report->type === 'found' ? 'Temuan' : 'Kehilangan' }}
                    </span>
                </div>

                {{-- DATE --}}
                <div class="absolute top-2 right-2
                            bg-white/90 backdrop-blur-sm
                            px-2 py-1 rounded text-xs font-semibold
                            text-gray-600 shadow-sm">
                    Ditemukan : {{ $report->event_date->format('d M Y') }}
                </div>
            </div>
        </a>

        <div class="p-4">
            <h3 class="text-lg font-bold text-gray-900 line-clamp-1">
                {!! highlight($report->item_name, $highlight ?? null) !!}
            </h3>

            <p class="text-sm text-gray-600 mt-2 line-clamp-2 h-10">
                {!! highlight($report->description, $highlight ?? null) !!}
            </p>

            <div class="flex items-start gap-2 text-sm text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 mt-0.5 text-gray-400 shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">
                <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 21s-6-5.686-6-10a6 6 0 1112 0c0 4.314-6 10-6 10z" />
                <circle cx="12" cy="11" r="2.5" />
                </svg>


                <span class="line-clamp-1">
                    {{ $report->room->name }},
                    <span class="text-gray-500">{{ $report->room->building->name }}</span>
                </span>
            </div>

            <div class="mt-4 flex justify-between items-center
                        border-t pt-3 text-sm">
                <span class="text-xs text-gray-400">
                    ID: #{{ $report->id }}
                </span>

                @if(!auth()->check() && $report->type === 'found')
                    <a href="{{ route('login') }}"
                        class="font-semibold text-blue-600 hover:text-blue-700">
                        Login untuk Klaim →
                    </a>
                @else
                    <a href="{{ route('reports.show', $report) }}"
                        class="font-medium text-primary hover:text-blue-700">
                        Lihat →
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
    @else
    {{-- EMPTY --}}
    <div class="text-center py-12 bg-white rounded-xl
                shadow-sm border border-gray-100">
        <div class="inline-flex items-center justify-center
                    w-16 h-16 rounded-full bg-gray-50 mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0
                        7 7 0 0114 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-800">
            Tidak Ada Barang
        </h3>
        <p class="text-sm text-gray-500 mt-1">
            Coba kata kunci atau filter lain.
        </p>
    </div>
@endif