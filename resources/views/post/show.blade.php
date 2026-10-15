<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-[minmax(0,1fr)_320px] xl:gap-10">

                <!-- Writer Sidebar -->
                <aside class="order-2 w-full lg:sticky lg:top-24 lg:order-2">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                        <x-follow-ctr :user="$post->user">
                            @if ($post->user->imageUrl())
                                <img src="{{ $post->user->imageUrl() }}" alt="Mudassar Mehdi"
                                    class="mx-auto h-28 w-28 rounded-full border-4 border-white object-cover shadow-md">
                            @else
                                <img src="data:image/svg+xml;utf8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22 viewBox=%220 0 300 300%22%3E%3Crect width=%22300%22 height=%22300%22 fill=%22hsl%28267,35%25,22%25%29%22/%3E%3Ccircle cx=%22150%22 cy=%22115%22 r=%2252%22 fill=%22%23fff%22 fill-opacity=%22.35%22/%3E%3Cpath d=%22M45 300c0-70 47-105 105-105s105 35 105 105z%22 fill=%22%23fff%22 fill-opacity=%22.35%22/%3E%3C/svg%3E"
                                    class="mx-auto h-28 w-28 rounded-full border-4 border-white object-cover shadow-md">
                            @endif

                            <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900">
                                {{ $post->user->name }}
                            </h1>

                            <p class="mt-1 text-sm font-medium text-slate-500">
                                {{ '@' . $post->user->username }}
                            </p>

                            <div class="flex gap-2 justify-center mt-3">
                                @if (auth()->user() && auth()->user()->id !== $post->user->id)
                                    <button @click="follow()" type="button" :aria-pressed="following"
                                        :class="following
                                            ?
                                            'border border-slate-300 bg-white text-slate-700 hover:bg-slate-100' :
                                            'text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-100'"
                                        class="flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition focus:outline-none focus:ring-4">
                                        <span x-text="following ? 'Unfollow' : 'Follow'"></span>
                                    </button>
                                    <a href="{{ route('profile.show', $post->user) }}"
                                        class="flex items-center justify-center gap-2 rounded-lg
           bg-gray-700 px-3 py-2 text-sm font-semibold text-white
           transition hover:bg-gray-800 focus:outline-none
           focus:ring-4 focus:ring-gray-200">
                                        <span>View Profile</span>
                                    </a>
                                @else
                                    <a href="#" aria-disabled="true"
                                        class="flex cursor-not-allowed items-center justify-center gap-2
           rounded-lg bg-gray-200 px-3 py-2 text-sm font-semibold
           text-gray-500 opacity-70">
                                        <span>Follow</span>
                                    </a>
                                    <a href="{{ route('profile.show', $post->user) }}"
                                        class="flex items-center justify-center gap-2 rounded-lg
           bg-gray-700 px-3 py-2 text-sm font-semibold text-white
           transition hover:bg-gray-800 focus:outline-none
           focus:ring-4 focus:ring-gray-200">
                                        <span>View Profile</span>
                                    </a>
                                @endif
                            </div>

                            <!-- Profile Stats -->
                            <div class="mt-4 grid grid-cols-3 border-y border-slate-100 px-3 py-5 text-center">

                                <button type="button" @click="$dispatch('open-follow-list', { tab: 'followers' })"
                                    class="rounded-lg focus:outline-none ">
                                    <p id="followersCount" class="text-lg font-bold text-slate-900"
                                        x-text="followersCount"></p>
                                    <p class="mt-1 text-xs text-slate-500">Followers</p>
                                </button>

                                <button type="button" @click="$dispatch('open-follow-list', { tab: 'following' })"
                                    class="border-x border-slate-100 focus:outline-none ">
                                    <p class="text-lg font-bold text-slate-900">
                                        {{ $post->user->following()->count() }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">Following</p>
                                </button>

                                <div>
                                    <p class="text-lg font-bold text-slate-900">
                                        {{ $post->user->posts()->count() }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">Posts</p>
                                </div>

                            </div>


                            <!-- Followers / Following Modal -->
                            @include('profile.partials.follow-list-modal', ['user' => $post->user])

                        </x-follow-ctr>



                        <!-- About Writer -->
                        <div class="px-6 pb-6">
                            <h2 class="text-sm font-bold text-slate-900">About</h2>

                            <ul class="mt-4 space-y-4 text-sm text-slate-600">
                                <li class="flex items-center gap-3">

                                    <span>{{ $post->user->bio }}</span>
                                </li>

                                <li class="flex items-center gap-3">
                                    @if ($post->user->countryCode())
                                        <span
                                            class="fi fi-{{ $post->user->countryCode() }} inline-block h-3 w-5 shrink-0 rounded-sm border border-gray-200"
                                            aria-hidden="true"></span>
                                    @endif
                                    <span>{{ $post->user->country ?? 'Unknown' }}
                                    </span>
                                </li>

                                <li class="flex items-center gap-3">
                                    <svg class="h-5 w-5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.8">
                                        <rect x="3" y="5" width="18" height="16" rx="2" />
                                        <path d="M16 3v4M8 3v4M3 11h18M8 15h2m4 0h2" />
                                    </svg>
                                    <span>Joined
                                        {{ Carbon\Carbon::parse($post->user->created_at)->format('M d, Y') }}</span>
                                </li>
                            </ul>


                        </div>

                    </div>
                </aside>


                <!-- Main Content -->
                <section class="order-1 min-w-0 lg:order-1">

                    <!-- Article Header -->
                    <header class="mb-8 sm:mb-10">

                        <!-- Category -->
                        <div class="mb-4">
                            <a href="{{ route('category.byCategory', $post->category) }}"
                                class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">
                                {{ $post->category->name }}
                            </a>
                        </div>

                        <!-- Title -->
                        <h1
                            class="text-3xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-4xl lg:text-4xl">
                            {{ $post->title }}
                        </h1>

                        <!-- Article Metadata -->
                        <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-3 border-b border-slate-200 pb-6">

                            <!-- Reading Time -->
                            <div class="inline-flex items-center gap-2 text-sm text-slate-500">
                                <svg class="h-5 w-5 shrink-0 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9" stroke-width="1.7" />
                                    <path d="M12 7v5l3 2" stroke-width="1.7" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span>
                                    <span class="font-semibold text-slate-700">{{ $post->readTime() }}</span>
                                    min read
                                </span>
                            </div>

                            <!-- Publication Date -->
                            <div class="inline-flex items-center gap-2 text-sm text-slate-500">
                                <svg class="h-5 w-5 shrink-0 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="5" width="18" height="16" rx="2" stroke-width="1.7" />
                                    <path d="M16 3v4M8 3v4M3 10h18" stroke-width="1.7" stroke-linecap="round" />
                                </svg>
                                <time datetime="{{ $post->created_at->toDateString() }}">
                                    {{ $post->created_at->format('M d, Y') }}
                                </time>
                            </div>

                            <!-- Clap Button -->
                            <div class="sm:ml-auto">

                                @if (Auth()->user())
                                    <x-clap-button :post="$post" />
                                @else 

                                    <button type="button" class="flex items-center gap-1.5 text-sm font-bold text-ink"
                                        aria-label="Like this post">
                                        <svg class="h-5 w-5 text-accent" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path
                                                d="M12 20.5s-7.5-4.6-9.2-9.4C1.6 7.7 3.7 4.5 7 4.5c2 0 3.4 1.1 5 3 1.6-1.9 3-3 5-3 3.3 0 5.4 3.2 4.2 6.6-1.7 4.8-9.2 9.4-9.2 9.4z" />
                                        </svg>
                                        <span>{{ $post->claps()->count() }}</span>
                                    </button>
                                @endif
                            </div>

                        </div>
                    </header>

                    <!-- Featured Image -->
                    @if ($post->imageUrl())
                        <figure
                            class="mb-8 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-sm">
                            <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}"
                                class="h-auto max-h-[660px] w-full object-cover" fetchpriority="high">
                        </figure>
                    @endif



                    <!-- Article Body -->
                    <article class="px-1 py-2 sm:px-3">

                        <div
                            class="prose prose-slate max-w-none text-base leading-8 text-slate-700 sm:text-lg sm:leading-9">
                            {!! nl2br(e($post->content)) !!}
                        </div>

                    </article>

                </section>
            </div>



        </div>
    </div>
</x-app-layout>
