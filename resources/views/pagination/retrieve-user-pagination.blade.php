<section>
    <!-- Pagination -->
    <div class="xs:mb-6 md:mb-6 mt-6 flex justify-center">
        <nav class="flex space-x-2" aria-label="Pagination">
            @php
            $totalPages = $users->lastPage();
            $currentPage = $users->currentPage();
            $maxPagesToShow = 5;
            $halfTotal = floor($maxPagesToShow / 2);

            $startPage = max(1, $currentPage - $halfTotal);
            $endPage = min($totalPages, $startPage + $maxPagesToShow - 1);

            if ($endPage - $startPage < $maxPagesToShow - 1) {
                $startPage=max(1, $endPage - $maxPagesToShow + 1);
            }
            @endphp

            <!-- Previous Button -->
            @if ($users->onFirstPage())
                <span class="px-4 py-2 text-gray-400 bg-gray-100 dark:bg-stone-800 dark:text-gray-500 rounded-md">
                    Prev
                </span>
            @else
                <a href="{{ $users->previousPageUrl() }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-stone-700 dark:bg-stone-700 dark:hover:bg-stone-950">
                    Prev
                </a>
            @endif

            <!-- Show first page and "..." if needed -->
            @if ($startPage > 1)
                <a href="{{ $users->url(1) }}"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 dark:bg-stone-700 dark:text-gray-200 dark:hover:bg-stone-600">
                    1
                </a>
                @if ($startPage > 2)
                    <span class="px-4 py-2 text-gray-400 dark:text-gray-500">...</span>
                @endif
            @endif

            <!-- Page Numbers -->
            @for ($page = $startPage; $page <= $endPage; $page++)
                @if ($page == $currentPage)
                    <span class="px-4 py-2 bg-stone-950 text-white font-bold rounded-md dark:bg-stone-950 dark:text-stone-100">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $users->url($page) }}"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 dark:bg-stone-700 dark:text-gray-200 dark:hover:bg-stone-600">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            <!-- Show last page and "..." if needed -->
            @if ($endPage < $totalPages)
                @if ($endPage < $totalPages - 1)
                    <span class="px-4 py-2 text-gray-400 dark:text-gray-500">...</span>
                @endif
                <a href="{{ $users->url($totalPages) }}"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 dark:bg-stone-700 dark:text-gray-200 dark:hover:bg-stone-600">
                    {{ $totalPages }}
                </a>
            @endif

            <!-- Next Button -->
            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-stone-700 dark:bg-stone-700 dark:hover:bg-stone-950">
                    Next
                </a>
            @else
                <span class="px-4 py-2 text-gray-400 bg-gray-100 dark:bg-stone-800 dark:text-gray-500 rounded-md">
                    Next
                </span>
            @endif
        </nav>
    </div>
</section>
