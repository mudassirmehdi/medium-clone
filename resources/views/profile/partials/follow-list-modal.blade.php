<div x-data="followListModal(
    '{{ route('profile.followers', $user) }}',
    '{{ route('profile.following', $user) }}',
    {{ auth()->check() ? 'true' : 'false' }},
    {{ auth()->id() ?? 'null' }}
)" @open-follow-list.window="open($event.detail.tab)" @keydown.escape.window="close()">
    <div x-cloak x-show="isOpen" x-transition.opacity
        class="fixed inset-0 z-[99999] flex items-center justify-center bg-slate-900/50 p-4" @click.self="close()"
        style="display: none;">
        <section role="dialog" aria-modal="true" aria-labelledby="follow-list-title"
            class="flex max-h-[80vh] w-full max-w-md flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 id="follow-list-title" class="text-lg font-bold text-slate-900"
                    x-text="activeTab === 'followers' ? 'Followers' : 'Following'"></h2>

                <button type="button" @click="close()"
                    class="rounded-full p-2 text-slate-500 hover:bg-slate-100 w-8 h-8 flex items-center justify-center"
                    aria-label="Close popup">
                    <span class="text-xl">&times;</span>
                </button>
            </div>

            <!-- Dynamic Tabs -->
            <div class="grid grid-cols-2 border-b border-slate-100">
                <button type="button" @click="switchTab('followers')"
                    :class="activeTab === 'followers'
                        ?
                        'border-b-2 border-blue-600 text-blue-600' :
                        'text-slate-500 hover:text-slate-800'"
                    class="py-3 text-sm font-semibold transition" :aria-selected="activeTab === 'followers'"
                    role="tab">
                    Followers
                    <span x-text="'(' + followersCount + ')'"></span>
                </button>

                <button type="button" @click="switchTab('following')"
                    :class="activeTab === 'following'
                        ?
                        'border-b-2 border-blue-600 text-blue-600' :
                        'text-slate-500 hover:text-slate-800'"
                    class="py-3 text-sm font-semibold transition" :aria-selected="activeTab === 'following'"
                    role="tab">
                    Following
                    <span x-text="'(' + followingCount + ')'"></span>
                </button>
            </div>

            <!-- List -->
            <!-- List -->
            <div class="min-h-40 overflow-y-auto p-4">
                <p x-show="loading" class="py-8 text-center text-sm text-slate-500">
                    Loading users...
                </p>

                <p x-show="!loading && error" x-text="error" class="py-6 text-center text-sm text-red-600"></p>

                <p x-show="!loading && !error && users.length === 0" class="py-8 text-center text-sm text-slate-500">
                    No profile found.
                </p>

                <template x-for="person in users" :key="person.id">
                    <div class="flex items-center gap-3 rounded-xl px-2 py-3 hover:bg-slate-50">

                        <!-- Profile link -->
                        <a :href="person.profile_url" class="flex min-w-0 flex-1 items-center gap-3">
                            <template x-if="person.avatar">
                                <img :src="person.avatar" :alt="person.name"
                                    class="h-11 w-11 shrink-0 rounded-full object-cover" />
                            </template>

                            <template x-if="!person.avatar">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-slate-100 font-semibold text-slate-600"
                                    x-text="person.name.charAt(0).toUpperCase()"></div>
                            </template>

                            <div class="min-w-0 flex-1 text-left">
                                <p class="truncate text-sm font-semibold text-slate-900" x-text="person.name"></p>

                                <p class="truncate text-sm text-slate-500" x-text="'@' + (person.username || '')"></p>
                            </div>
                        </a>

                        <!-- Follow / Unfollow button -->
                        <template x-if="isAuthenticated && person.id !== currentUserId">
                            <button type="button" @click.stop="toggleFollow(person)" :disabled="person.followLoading"
                                :aria-pressed="person.is_following"
                                :class="person.is_following ?
                                    'border border-slate-300 bg-white text-slate-700 hover:bg-slate-100' :
                                    'bg-blue-600 text-white hover:bg-blue-700'"
                                class="shrink-0 rounded-lg px-3 py-2 text-xs font-semibold transition disabled:cursor-not-allowed disabled:opacity-60">
                                <span
                                    x-text="person.followLoading
                            ? 'Please wait...'
                            : (person.is_following ? 'Unfollow' : 'Follow')"></span>
                            </button>
                        </template>

                        <!-- Guest users must sign in to follow -->
                        <template x-if="!isAuthenticated">
                            <a href="#" aria-disabled="true"
                                class="flex cursor-not-allowed items-center justify-center gap-2
           rounded-lg bg-gray-200 px-3 py-2 text-sm font-semibold
           text-gray-500 opacity-70">
                                <span>Follow</span>
                            </a>
                        </template>

                    </div>
                </template>

                <!-- Load More -->
                <div x-show="!loading && users.length && nextPageUrl" class="pt-3 text-center">
                    <button type="button" @click="loadMore()" :disabled="loadingMore"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:opacity-50">
                        <span x-text="loadingMore ? 'Loading...' : 'Load more'"></span>
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>
