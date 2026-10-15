
 
    <ul class="flex flex-wrap items-center justify-center gap-2 sm:gap-3
               text-center text-sm font-medium text-gray-500">

        <li class="max-w-full">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center rounded-full
                       border border-gray-200 px-4 py-2.5
                       text-sm font-medium transition-colors
                       focus:outline-none focus:ring-2 focus:ring-gray-300
                       {{ request()->routeIs('dashboard')
                            ? 'bg-gray-900 text-white hover:bg-gray-800'
                            : 'bg-white text-gray-900 hover:bg-gray-100 hover:text-blue-700' }}">
                All
                 {{-- {{ \App\Models\Post::count() }} --}}
            </a>
        </li>

        @forelse ($categories as $category)
            <li class="max-w-full">
                <a href="{{ route('category.byCategory', $category) }}"
                    class="inline-flex max-w-full items-center justify-center
                           break-words rounded-full border border-gray-200
                           px-4 py-2.5 text-sm font-medium transition-colors
                           focus:outline-none focus:ring-2 focus:ring-gray-300
                           {{ request()->routeIs('category.byCategory') && request()->route('category')?->id == $category->id
                                ? 'bg-gray-900 text-white hover:bg-gray-800'
                                : 'bg-white text-gray-900 hover:bg-gray-100 hover:text-blue-700' }}">
                    {{ $category->name }} 
                    {{-- {{ $category->posts()->count() }} --}}
                </a>
            </li>
        @empty
            <li>
                <p class="px-4 py-2 text-sm text-gray-500">
                    {{ $slot }}
                </p>
            </li>
        @endforelse

    </ul> 
