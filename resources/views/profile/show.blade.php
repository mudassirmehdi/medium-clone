<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="text-gray-900 ">


                <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-[320px_minmax(0,1fr)] xl:gap-10">

                    <!-- Writer Sidebar -->
                    <aside class="w-full lg:sticky lg:top-24">
                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                            <!-- Profile Header -->
                            <x-follow-ctr :user="$user">

                                @if ($user->imageUrl())
                                    <img src="{{ $user->imageUrl() }}" alt="Mudassar Mehdi"
                                        class="mx-auto h-28 w-28 rounded-full border-4 border-white object-cover shadow-md">
                                @else
                                    <img src="data:image/svg+xml;utf8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22 viewBox=%220 0 300 300%22%3E%3Crect width=%22300%22 height=%22300%22 fill=%22hsl%28267,35%25,22%25%29%22/%3E%3Ccircle cx=%22150%22 cy=%22115%22 r=%2252%22 fill=%22%23fff%22 fill-opacity=%22.35%22/%3E%3Cpath d=%22M45 300c0-70 47-105 105-105s105 35 105 105z%22 fill=%22%23fff%22 fill-opacity=%22.35%22/%3E%3C/svg%3E"
                                        class="mx-auto h-28 w-28 rounded-full border-4 border-white object-cover shadow-md">
                                @endif

                                <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900">
                                    {{ $user->name }}
                                </h1>

                                <p class="mt-1 text-sm font-medium text-slate-500">
                                    {{ '@' . $user->username }}
                                </p>




                                <div class="mt-3 flex justify-center gap-2">

                                    @auth
                                        @if (auth()->id() === $user->id)
                                            <a href="{{ route('profile.edit', $user) }}"
                                                class="flex items-center justify-center rounded-lg
                       bg-gray-700 px-3 py-2 text-sm font-semibold
                       text-white transition hover:bg-gray-800
                       focus:outline-none focus:ring-4 focus:ring-gray-200">
                                                Edit Profile
                                            </a>
                                        @else
                                            <button @click="follow()" type="button" :aria-pressed="following"
                                                :disabled="followLoading"
                                                :class="following
                                                    ?
                                                    'border border-slate-300 bg-white text-slate-700 hover:bg-slate-100' :
                                                    'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-100'"
                                                class="flex items-center justify-center gap-2 rounded-lg
                       px-3 py-2 text-sm font-semibold transition
                       focus:outline-none focus:ring-4 disabled:opacity-60">

                                                <span
                                                    x-text="followLoading
                        ? 'Please wait...'
                        : (following ? 'Unfollow' : 'Follow')">
                                                </span>
                                            </button>
                                        @endif
                                    @else
                                        <button type="button" disabled aria-disabled="true" title="Log in to follow users"
                                            class="flex cursor-not-allowed items-center justify-center
                   rounded-lg bg-gray-200 px-3 py-2 text-sm
                   font-semibold text-gray-500 opacity-70">
                                            Follow
                                        </button>
                                    @endauth

                                </div>




                                <!-- Profile Stats -->
                                <div class="mt-4 grid grid-cols-3 border-y border-slate-100 px-3 py-5 text-center">

                                    <button type="button" @click="$dispatch('open-follow-list', { tab: 'followers' })"
                                        class="rounded-lg focus:outline-none">
                                        <p id="followersCount" class="text-lg font-bold text-slate-900"
                                            x-text="followersCount"></p>
                                        <p class="mt-1 text-xs text-slate-500">Followers</p>
                                    </button>

                                    <button type="button" @click="$dispatch('open-follow-list', { tab: 'following' })"
                                        class="border-x border-slate-100 focus:outline-none">
                                        <p class="text-lg font-bold text-slate-900">
                                            {{ $user->following()->count() }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">Following</p>
                                    </button>

                                    <div>
                                        <p class="text-lg font-bold text-slate-900">
                                            {{ $user->posts()->count() }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">Posts</p>
                                    </div>

                                </div>

                                <!-- Followers / Following Modal -->
                                @include('profile.partials.follow-list-modal', ['user' => $user])

                            </x-follow-ctr>



                            <!-- About Writer -->
                            <div class="p-6">
                                <h2 class="text-sm font-bold text-slate-900">About</h2>

                                <ul class="mt-4 space-y-4 text-sm text-slate-600">
                                    <li class="flex items-center gap-3">

                                        <span> {{ $user->bio }}</span>
                                    </li>

                                    <li class="flex items-center gap-3">
                                        @if ($user->countryCode())
                                            <span
                                                class="fi fi-{{ $user->countryCode() }} inline-block h-3 w-5 shrink-0 rounded-sm border border-gray-200"
                                                aria-hidden="true"></span>
                                        @endif
                                        <span>{{ $user->country ?? 'Unknown' }}
                                        </span>
                                    </li>

                                    <li class="flex items-center gap-3">
                                        <svg class="h-5 w-5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8">
                                            <rect x="3" y="5" width="18" height="16" rx="2" />
                                            <path d="M16 3v4M8 3v4M3 11h18M8 15h2m4 0h2" />
                                        </svg>
                                        <span>Joined
                                            {{ Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</span>
                                    </li>
                                </ul>

                                <div class="mt-6 border-t border-slate-100 pt-5">
                                    <h3 class="text-sm font-semibold text-slate-900">
                                        Writing about
                                    </h3>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @forelse ($user->posts->pluck('category')->filter()->unique('id') as $category)
                                            <a href="{{ route('category.byCategory', $category) }}"
                                                class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">{{ $category->name }}</a>
                                        @empty
                                            <p class="text-xs text-gray-500">
                                                No post published yet.
                                            </p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                        </div>
                    </aside>

                    <!-- Main Content -->
                    <section class="min-w-0">

                        <!-- Section Heading -->
                        <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                            <div>
                                {{-- <p class="text-sm font-medium text-blue-600">Writer Profile</p> --}}
                                <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                                    Posts
                                </h2>
                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    Read the latest articles and thoughts from
                                    <span class="font-medium text-slate-700">{{ $user->name }}</span>
                                </p>
                            </div>

                            <div class="text-sm text-slate-500">
                                Showing <span class="font-semibold text-slate-700">{{ $user->posts()->count() }}</span>
                                articles
                            </div>
                        </div>

                        <!-- Blog Posts Grid -->
                        <div @class([
                            'grid grid-cols-1 items-start  gap-4 xl:gap-5',
                            'lg:grid-cols-3' => $posts->isNotEmpty(),
                        ])>

                            @forelse ($posts as $post)
                                <x-post-item :post="$post" />
                            @empty 

                                <div class="flex flex-col items-center justify-center px-6 py-16 text-center">
                                    <div
                                        class="mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                                        <svg class="h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-9a1.5 1.5 0 0 0-1.5-1.5h-12a1.5 1.5 0 0 0-1.5 1.5v15a1.5 1.5 0 0 0 1.5 1.5h9m-9-13.5h9m-9 4.5h6m-6 4.5h3m5.25-1.5v6m-3-3h6" />
                                        </svg>
                                    </div>

                                    <h3 class="mb-2 text-xl font-semibold tracking-tight text-gray-900">
                                        No posts found
                                    </h3> 
                                </div>
                            @endforelse





                        </div>

                        <!-- Pagination -->
                        {{ $posts->links() }}

                    </section>
                </div>


            </div>
        </div>
    </div>
</x-app-layout>
