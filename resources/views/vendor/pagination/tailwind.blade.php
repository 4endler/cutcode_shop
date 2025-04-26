@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="">
        <ul class="flex flex-wrap items-center justify-center gap-3">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li><span aria-disabled="true" class="text-body/50 text-sm font-black leading-none">{{$element}}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page"><a href="#" class="block p-3 pointer-events-none text-pink text-sm font-black leading-none">{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}" class="block p-3 text-white hover:text-pink text-sm font-black leading-none">{{ $page }}</a></li>                                
                        @endif
                    @endforeach
                @endif
            @endforeach
        </ul>
    </nav>
@endif
