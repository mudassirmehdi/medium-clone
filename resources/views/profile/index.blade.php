<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Find People') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div @class([
                'grid grid-cols-1 items-start  gap-4 xl:gap-5',
                'lg:grid-cols-4' => $users->isNotEmpty(),
            ])>
                @forelse ($users as $user)
                    <div class="overflow-hidden rounded-2xl h-full border border-slate-200 bg-white shadow-sm">
                        <x-follow-ctr :user="$user">

                            @if ($user->imageUrl())
                                <img src="{{ $user->imageUrl() }}" alt="{{ $user->name }}"
                                    class="mx-auto h-20 w-20 rounded-full border-4 border-white object-cover shadow-md">
                            @else
                                <img src="data:image/svg+xml;utf8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22 viewBox=%220 0 300 300%22%3E%3Crect width=%22300%22 height=%22300%22 fill=%22hsl%28267,35%25,22%25%29%22/%3E%3Ccircle cx=%22150%22 cy=%22115%22 r=%2252%22 fill=%22%23fff%22 fill-opacity=%22.35%22/%3E%3Cpath d=%22M45 300c0-70 47-105 105-105s105 35 105 105z%22 fill=%22%23fff%22 fill-opacity=%22.35%22/%3E%3C/svg%3E"
                                    class="mx-auto h-20 w-20 rounded-full border-4 border-white object-cover shadow-md">
                            @endif

                            <h1 class="mt-3 text-1xl font-bold tracking-tight text-slate-900">
                                {{ $user->name }}
                            </h1>

                            <p class="mt-1 text-xs font-medium text-slate-500">
                                {{ '@' . $user->username }}
                            </p>

                            <div class="inline-flex items-center gap-2 mt-3">
                                @if ($user->countryCode())
                                    <span
                                        class="fi fi-{{ $user->countryCode() }} inline-block h-3 w-5 shrink-0 rounded-sm border border-gray-200"
                                        aria-hidden="true"></span>
                                @endif

                                <span class="text-xs font-medium text-gray-500">
                                    {{ $user->country }}
                                </span>
                            </div>

                            {{-- <p class="mt-3 text-xs leading-6 text-slate-600">
                                {{ Str::words(strip_tags($user->bio), 7) }}
                            </p> --}}

                            <div class="flex gap-2 justify-center mt-3">
                                @if (auth()->user() && auth()->user()->id !== $user->id)
                                    <button @click="follow()" type="button" :aria-pressed="following"
                                        :class="following
                                            ?
                                            'border border-slate-300 bg-white text-slate-700 hover:bg-slate-100' :
                                            'text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-100'"
                                        class="flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold  transition focus:outline-none focus:ring-4">
                                        <span x-text="following ? 'Unfollow' : 'Follow'"></span>
                                    </button>
                                    <a href="{{ route('profile.show', $user) }}"
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
                                    <a href="{{ route('profile.show', $user) }}"
                                        class="flex items-center justify-center gap-2 rounded-lg
           bg-gray-700 px-3 py-2 text-sm font-semibold text-white
           transition hover:bg-gray-800 focus:outline-none
           focus:ring-4 focus:ring-gray-200">
                                        <span>View Profile</span>
                                    </a>
                                @endif
                            </div>



                            <!-- Profile Stats -->
                            <div class="mt-5 grid grid-cols-3 border-t border-slate-100 px-3 py-5 text-center">
                                <div>
                                    <p id="followersCount" class="text-lg font-bold text-slate-900"
                                        x-text="followersCount"></p>
                                    <p class="mt-1 text-xs text-slate-500">Followers</p>
                                </div>

                                <div class="border-x border-slate-100">
                                    <p class="text-lg font-bold text-slate-900">{{ $user->following()->count() }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">Following</p>
                                </div>

                                <div>
                                    <p class="text-lg font-bold text-slate-900">{{ $user->posts()->count() }}</p>
                                    <p class="mt-1 text-xs text-slate-500">Posts</p>
                                </div>
                            </div>
                        </x-follow-ctr>
                    </div>
                @empty

                    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                        <div class="flex items-center justify-center w-16 h-16 mb-5 rounded-full bg-gray-100">

                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="10" cy="8" r="4" />
                                <path d="M3 21v-2a7 7 0 0 1 11.5-5.35" />
                                <path d="m15 19 5.5-5.5a1.4 1.4 0 0 1 2 2L17 21l-3 1z" />
                            </svg>

                        </div>

                        <h2 class="text-xl font-semibold text-gray-900">
                            No Writer Found
                        </h2>

                        <p class="mt-2 max-w-md text-sm leading-6 text-gray-500">
                            There are no writer to show you right now.
                            Checkout <a href="{{ route('dashboard') }}" class="underline">discover</a> for latest
                            stories and updates from followed writers.
                        </p>

                        <a href="{{ route('dashboard') }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-full
        bg-gray-900 px-5 py-2.5 text-sm font-medium text-white
        transition hover:bg-gray-700">
                            <span>Discover Now</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                @endforelse
            </div>
            <div class="w-full mt-8 flex justify-center items-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
