<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-5">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Discover') }}
            </h2>
            <x-category-tabs>
                No Category Found
            </x-category-tabs>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="text-gray-900"> 
                <!-- Grid Container -->
                <div @class([
                    'grid grid-cols-1 justify-items-center gap-8  ',
                    'lg:grid-cols-3' => $posts->isNotEmpty(),
                ])>

                    @forelse ($posts as $post)
                        <x-post-item :post="$post" />
                    @empty

                        @if (isset($category))
                            @if (!$categoryHasPosts)
                                <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                                    <div
                                        class="flex items-center justify-center w-16 h-16 mb-5 rounded-full bg-gray-100">
                                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>

                                    <h2 class="text-xl font-semibold text-gray-900">
                                        No posts in {{ $category->name }} yet
                                    </h2>

                                    <p class="mt-2 max-w-md text-sm leading-6 text-gray-500">
                                        There are no posts in this category yet.
                                        Explore other categories to discover more stories.
                                    </p>

                                    <a href="{{ route('dashboard') }}"
                                        class="mt-6 inline-flex items-center gap-2 rounded-full
        bg-gray-900 px-5 py-2.5 text-sm font-medium text-white
        transition hover:bg-gray-700">
                                        <span>Explore all posts</span>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                                    <div
                                        class="flex items-center justify-center w-16 h-16 mb-5 rounded-full bg-gray-100">
                                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2
                c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0
                015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857
                m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0
                11-6 0 3 3 0 016 0zm6 3a2 2 0
                11-4 0 2 2 0 014 0zM7 10a2 2 0
                11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>

                                    <h2 class="text-xl font-semibold text-gray-900">
                                        No posts from writers you follow
                                    </h2>

                                    <p class="mt-2 max-w-md text-sm leading-6 text-gray-500">
                                        There are posts in {{ $category->name }},
                                        but none from the writers you follow.
                                        Discover more writers to fill your feed.
                                    </p>

                                    <a href="{{ route('profile.index') }}"
                                        class="mt-6 inline-flex items-center gap-2 rounded-full
        bg-gray-900 px-5 py-2.5 text-sm font-medium text-white
        transition hover:bg-gray-700">
                                        <span>Discover writers</span>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                                <div class="flex items-center justify-center w-16 h-16 mb-5 rounded-full bg-gray-100">
                                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2
                c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0
                015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857
                m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0
                11-6 0 3 3 0 016 0zm6 3a2 2 0
                11-4 0 2 2 0 014 0zM7 10a2 2 0
                11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>

                                <h2 class="text-xl font-semibold text-gray-900">
                                    Your feed is looking a little quiet
                                </h2>

                                <p class="mt-2 max-w-md text-sm leading-6 text-gray-500">
                                    There are no posts to show you right now.
                                    Follow writers you love to discover their latest stories and updates.
                                </p>

                                <a href="{{ route('profile.index') }}"
                                    class="mt-6 inline-flex items-center gap-2 rounded-full
        bg-gray-900 px-5 py-2.5 text-sm font-medium text-white
        transition hover:bg-gray-700">
                                    <span>Discover writers</span>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        @endif


                    @endforelse
                </div>
                <div class="w-full mt-8 flex justify-center items-center">
                    {{ $posts->links() }}
                </div>


            </div>
        </div>
    </div>
</x-app-layout>
