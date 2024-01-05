<ul class="pagination">
    <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $paginator->previousPageUrl() }}">Anterior</a>
    </li>
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <li class="page-item disabled"><a class="page-link">{{ $element }}</a></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                <li class="page-item {{ $paginator->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach
        @endif
    @endforeach
    <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
        <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Siguiente</a>
    </li>
</ul>
