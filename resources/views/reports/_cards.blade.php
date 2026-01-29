@php
function highlight($text, $word) {
    if (!$word) return e($text);

    return preg_replace(
        '/' . preg_quote($word, '/') . '/i',
        '<mark class="bg-yellow-200 rounded">$0</mark>',
        e($text)
    );
}
@endphp

@if($reports->count() > 0)

@foreach($reports as $report)
<div class="bg-white rounded-xl shadow-sm overflow-hidden
            border border-gray-100
            hover:shadow-xl transition-all duration-300 group">

    {{-- IMAGE --}}
    <a href="{{ route('reports.show', $report) }}" class="block relative">
        <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
            @if($report->photo)
                <img src="{{ Storage::url($report->photo) }}"
                     loading="lazy"
                     class="w-full h-full object-cover
                            transition-transform duration-500
                            group-hover:scale-105">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-200">
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
                {{ $report->event_date->format('d M Y') }}
            </div>
        </div>
    </a>

    {{-- CONTENT --}}
    <div class="p-4">
        <h3 class="text-lg font-bold text-gray-900 line-clamp-1">
            {!! highlight($report->item_name, $highlight ?? null) !!}
        </h3>

        <p class="text-sm text-gray-600 mt-2 line-clamp-2 min-h-[2.5rem]">
            {!! highlight($report->description, $highlight ?? null) !!}
        </p>

        <div class="mt-3 flex items-start gap-2 text-sm text-gray-700">
            <svg class="w-4 h-4 mt-0.5 text-gray-400 shrink-0"
                 fill="none" stroke="currentColor"
                 viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 21s-6-5.686-6-10a6 6 0 1112 0z" />
                <circle cx="12" cy="11" r="2.5" />
            </svg>
            <span class="line-clamp-1">
                {!! highlight($report->room->name, $highlight ?? null) !!},
                <span class="text-gray-500">
                    {!! highlight($report->room->building->name, $highlight ?? null) !!}
                </span>
            </span>
        </div>

        <div class="mt-4 flex justify-between items-center border-t pt-3 text-sm">
            <span class="text-xs text-gray-400">ID: #{{ $report->id }}</span>

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

@if ($reports->hasPages())
    <div class="col-span-full flex justify-center mt-12">
        {{ $reports->onEachSide(1)->links() }}
    </div>
@endif

@else
<div class="col-span-full text-center py-16">
    <p class="text-gray-500">Tidak ada barang ditemukan.</p>
</div>
@endif
