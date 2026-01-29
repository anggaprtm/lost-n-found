@if ($paginator->hasPages())
<nav class="flex justify-center">
    <ul class="inline-flex items-center gap-1 rounded-lg bg-white shadow-sm border border-gray-200 px-2 py-1">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li class="px-3 py-2 text-gray-400 cursor-not-allowed">
                ‹
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-3 py-2 rounded-md hover:bg-gray-100 text-gray-600">
                    ‹
                </a>
            </li>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="px-3 py-2 text-gray-400">{{ $element }}</li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li>
                            <span class="px-3 py-2 rounded-md bg-primary text-white font-semibold">
                                {{ $page }}
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}"
                               class="px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
                                {{ $page }}
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-3 py-2 rounded-md hover:bg-gray-100 text-gray-600">
                    ›
                </a>
            </li>
        @else
            <li class="px-3 py-2 text-gray-400 cursor-not-allowed">
                ›
            </li>
        @endif
    </ul>
</nav>
@endif
