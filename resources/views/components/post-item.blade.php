<article
    class="group flex h-full min-w-0 flex-col overflow-hidden rounded-2xl
           border border-slate-200 bg-white shadow-sm
           transition duration-300 hover:-translate-y-1 hover:shadow-xl">

    {{-- Post image --}}
    <a href="{{ route('post.show', [
        'username' => $post->user->username,
        'post' => $post->slug,
    ]) }}"
        class=" block aspect-[16/9] z-1 overflow-hidden bg-slate-100">

        @if ($post->imageUrl())
            <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}"
                class="h-full w-full object-cover transition duration-500
                       group-hover:scale-105"
                loading="lazy">
        @else
            <div class="flex h-full items-center justify-center">
                <svg class="h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 5h16v14H4zM4 15l5-5 4 4 2-2 5 5" />
                </svg>
            </div>
        @endif
    </a>

    {{-- Post content --}}
    <div class="flex flex-1 flex-col p-4 sm:p-5">

        {{-- Category and date --}}
        <div class="flex flex-wrap items-center justify-between gap-2">
            <a href="{{ route('category.byCategory', $post->category) }}"
                class="inline-flex max-w-full rounded-full bg-blue-50
                      px-3 py-1 text-xs font-semibold text-blue-700
                      transition hover:bg-blue-100">
                {{ $post->category->name }}
            </a>

            <span class="text-xs text-slate-500">
                {{ Carbon\Carbon::parse($post->created_at)->format('M d, Y') }} &middot; {{ $post->readTime() }} min
                read
            </span>
        </div>

        {{-- Title --}}
        <h3
            class="mt-4 text-lg font-bold leading-6 text-slate-900
                   transition group-hover:text-blue-600 sm:text-xl">
            <a
                href="{{ route('post.show', [
                    'username' => $post->user->username,
                    'post' => $post->slug,
                ]) }}">
                {{ Str::words($post->title, 8) }}
            </a>
        </h3>

        {{-- Excerpt --}}
        <p class="mt-3 mb-2 text-sm leading-6 text-slate-600">
            {{ Str::words(strip_tags($post->content), 10) }}
        </p>

        {{-- Writer and reading time --}}
        {{-- Writer and reading time --}}
        <div class="mt-auto flex items-center justify-between gap-3
            border-t border-slate-100 pt-4">

            {{-- Writer information --}}
            <a href="{{ route('profile.show', $post->user->username) }}" class="flex min-w-0 items-center gap-2.5">

                @if ($post->user->imageUrl())
                    <img src="{{ $post->user->imageUrl() }}" alt="{{ $post->user->name }}"
                        class="h-10 w-10 shrink-0 rounded-full object-cover">
                @else
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center
                        rounded-full bg-slate-100 text-sm font-semibold text-slate-600">
                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                    </div>
                @endif

                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-slate-800">
                        {{ $post->user->name }}
                    </p>
                    <p class="truncate text-xs text-slate-500">
                        {{ '@' . $post->user->username }}
                    </p>
                </div>
            </a>

            {{-- Reading time --}}

            @if (Auth()->user())
                <x-clap-button :post="$post" />
            @else
                <button type="button" class="flex items-center gap-1.5 text-sm font-bold text-ink"
                    aria-label="Like this post">
                    <svg class="h-5 w-5 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path
                            d="M12 20.5s-7.5-4.6-9.2-9.4C1.6 7.7 3.7 4.5 7 4.5c2 0 3.4 1.1 5 3 1.6-1.9 3-3 5-3 3.3 0 5.4 3.2 4.2 6.6-1.7 4.8-9.2 9.4-9.2 9.4z" />
                    </svg>
                    <span>{{ $post->claps()->count() }}</span>
                </button>
            @endif

        </div>
    </div>
</article>
