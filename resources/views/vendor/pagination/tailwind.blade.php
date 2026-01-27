@if ($paginator->hasPages())
    <nav class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-gray-400 bg-white border rounded-md">Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-50">Previous</a>
            @endif
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="ml-3 px-4 py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-50">Next</a>
            @else
                <span class="ml-3 px-4 py-2 text-gray-400 bg-white border rounded-md">Next</span>
            @endif
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <p class="text-sm text-gray-700">
                Showing <span class="font-medium">{{ $paginator->firstItem() }}</span> to <span class="font-medium">{{ $paginator->lastItem() }}</span> of <span class="font-medium">{{ $paginator->total() }}</span>
            </p>
            <div class="flex space-x-1">
                @if ($paginator->onFirstPage())
                    <span class="px-3 py-1 text-gray-400 bg-white border rounded">&laquo;</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 text-gray-700 bg-white border rounded hover:bg-gray-50">&laquo;</a>
                @endif
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 text-gray-700 bg-white border rounded hover:bg-gray-50">&raquo;</a>
                @else
                    <span class="px-3 py-1 text-gray-400 bg-white border rounded">&raquo;</span>
                @endif
            </div>
        </div>
    </nav>
@endif
