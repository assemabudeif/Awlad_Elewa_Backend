@if ($paginator->hasPages())
<nav aria-label="الصفحات">
    <ul class="pagination pagination-sm justify-content-center mb-0">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <li class="page-item disabled" aria-disabled="true">
            <span class="page-link">
                <i class="fas fa-chevron-right"></i>
                <span class="sr-only">السابق</span>
            </span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                <i class="fas fa-chevron-right"></i>
                <span class="sr-only">السابق</span>
            </a>
        </li>
        @endif

        {{-- First Page Link --}}
        @if($paginator->currentPage() > 3)
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
        </li>
        @if($paginator->currentPage() > 4)
        <li class="page-item disabled">
            <span class="page-link">...</span>
        </li>
        @endif
        @endif

        {{-- Pagination Elements --}}
        @for ($i = max(1, $paginator->currentPage() - 2); $i <= min($paginator->lastPage(), $paginator->currentPage() + 2); $i++)
            @if ($i == $paginator->currentPage())
            <li class="page-item active" aria-current="page">
                <span class="page-link">{{ $i }}</span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
            </li>
            @endif
            @endfor

            {{-- Last Page Link --}}
            @if($paginator->currentPage() < $paginator->lastPage() - 2)
                @if($paginator->currentPage() < $paginator->lastPage() - 3)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                    </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                            <span class="sr-only">التالي</span>
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">
                            <span class="sr-only">التالي</span>
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                    @endif
    </ul>
</nav>

{{-- Results Info --}}
<div class="d-flex justify-content-center mt-2">
    <small class="text-muted">
        عرض {{ $paginator->firstItem() }} إلى {{ $paginator->lastItem() }} من أصل {{ $paginator->total() }} نتيجة
    </small>
</div>
@endif